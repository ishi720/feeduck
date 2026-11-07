import _ from 'lodash';
import Popper from 'popper.js';
import $ from 'jquery';
import 'bootstrap';
import axios from 'axios';

window._ = _;

/**
 * jQuery と Bootstrap の jQuery プラグイン(ドロップダウンなど)を読み込む
 */
window.Popper = Popper;
window.$ = window.jQuery = $;

/**
 * axios の共通設定
 */
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
