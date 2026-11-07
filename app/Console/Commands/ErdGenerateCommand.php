<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ErdGenerateCommand extends Command
{
    private const START_MARKER = '<!-- ER_DIAGRAM:START -->';
    private const END_MARKER = '<!-- ER_DIAGRAM:END -->';

    /**
     * @var string
     */
    protected $signature = 'erd:generate
                            {--output= : 出力先ファイル（未指定なら config/erd.php の output）}
                            {--stdout : ファイルに書き込まず標準出力に表示する}';

    /**
     * @var string
     */
    protected $description = 'DBのスキーマからMermaidのER図を生成する';

    public function handle()
    {
        $mermaid = $this->buildMermaid();

        if ($this->option('stdout')) {
            $this->line($mermaid);
            return self::SUCCESS;
        }

        $path = $this->option('output') ?: config('erd.output', 'docs/er.md');
        if (! preg_match('#^([A-Za-z]:)?[\\\\/]#', $path)) {
            $path = base_path($path);
        }
        $this->writeToFile($path, $mermaid);
        $this->info("ER図を更新しました: {$path}");

        return self::SUCCESS;
    }

    /**
     * スキーマを読み取ってMermaidのコードブロックを組み立てる
     */
    private function buildMermaid(): string
    {
        $exclude = config('erd.exclude', []);
        $tables = collect(Schema::getTables(Schema::getCurrentSchemaListing()))
            ->pluck('name')
            ->reject(fn ($name) => in_array($name, $exclude, true))
            ->sort()
            ->values();

        $relations = [];
        $entities = [];

        foreach ($tables as $table) {
            $columns = Schema::getColumns($table);
            $indexes = Schema::getIndexes($table);

            $primary = [];
            $unique = [];
            foreach ($indexes as $index) {
                if ($index['primary']) {
                    $primary = array_merge($primary, $index['columns']);
                } elseif ($index['unique'] && count($index['columns']) === 1) {
                    $unique[] = $index['columns'][0];
                }
            }

            // 外部キー制約（DB）＋ 設定ファイルで指定したリレーション
            $foreign = [];
            foreach (Schema::getForeignKeys($table) as $fk) {
                if (count($fk['columns']) === 1) {
                    $foreign[$fk['columns'][0]] = $fk['foreign_table'];
                }
            }
            foreach (config('erd.relations', []) as $child => $parent) {
                [$childTable, $childColumn] = explode('.', $child, 2);
                [$parentTable] = explode('.', $parent, 2);
                if ($childTable === $table && $tables->contains($parentTable)) {
                    $foreign[$childColumn] = $parentTable;
                }
            }

            $lines = [];
            foreach ($columns as $column) {
                $name = $column['name'];

                $keys = [];
                if (in_array($name, $primary, true)) {
                    $keys[] = 'PK';
                }
                if (isset($foreign[$name])) {
                    $keys[] = 'FK';
                }
                if (in_array($name, $unique, true)) {
                    $keys[] = 'UK';
                }

                $notes = [];
                if ($column['nullable']) {
                    $notes[] = 'nullable';
                }
                if ($column['comment']) {
                    $notes[] = $column['comment'];
                }

                $line = '        ' . $this->typeName($column) . ' ' . $name;
                if ($keys) {
                    $line .= ' ' . implode(',', $keys);
                }
                if ($notes) {
                    $line .= ' "' . str_replace('"', "'", implode(' / ', $notes)) . '"';
                }
                $lines[] = $line;

                if (isset($foreign[$name])) {
                    // 子側: NULL可なら0以上、ユニークなら1対1
                    $childSide = in_array($name, $unique, true) ? 'o|' : 'o{';
                    $parentSide = $column['nullable'] ? '|o' : '||';
                    $relations[] = "    {$foreign[$name]} {$parentSide}--{$childSide} {$table} : \"{$name}\"";
                }
            }

            $entities[] = "    {$table} {\n" . implode("\n", $lines) . "\n    }";
        }

        return implode("\n", array_merge(
            ['```mermaid', 'erDiagram'],
            $relations,
            $relations ? [''] : [],
            [implode("\n\n", $entities)],
            ['```']
        ));
    }

    /**
     * Mermaidの型名として使える文字列にする（例: "varchar(255)" → "varchar"）
     */
    private function typeName(array $column): string
    {
        $type = $column['type_name'] ?: $column['type'];
        $type = preg_replace('/[^A-Za-z0-9_]/', '_', strtolower($type));

        return $type !== '' ? $type : 'unknown';
    }

    /**
     * マーカーの間だけを置き換える（マーカーの外に書いた説明文は残る）。
     * ファイルが無い、またはマーカーが無い場合はER図の節を追加する
     */
    private function writeToFile(string $path, string $mermaid): void
    {
        $block = self::START_MARKER . "\n\n" . $mermaid . "\n\n" . self::END_MARKER;
        $content = is_file($path) ? file_get_contents($path) : '';

        $pattern = '/' . preg_quote(self::START_MARKER, '/') . '.*?' . preg_quote(self::END_MARKER, '/') . '/s';

        if (preg_match($pattern, $content)) {
            $content = preg_replace_callback($pattern, fn () => $block, $content, 1);
        } else {
            $section = "# ER図\n\n"
                . "`php artisan erd:generate` で自動生成しています。\n\n"
                . $block . "\n";
            $content = trim($content) === '' ? $section : rtrim($content) . "\n\n" . $section;
        }

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $content);
    }
}
