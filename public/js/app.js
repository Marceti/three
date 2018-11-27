/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(2);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: Couldn't find preset \"env\" relative to directory \"/home/vagrant/code/resources/js\"\n    at /home/vagrant/code/node_modules/babel-core/lib/transformation/file/options/option-manager.js:293:19\n    at Array.map (<anonymous>)\n    at OptionManager.resolvePresets (/home/vagrant/code/node_modules/babel-core/lib/transformation/file/options/option-manager.js:275:20)\n    at OptionManager.mergePresets (/home/vagrant/code/node_modules/babel-core/lib/transformation/file/options/option-manager.js:264:10)\n    at OptionManager.mergeOptions (/home/vagrant/code/node_modules/babel-core/lib/transformation/file/options/option-manager.js:249:14)\n    at OptionManager.init (/home/vagrant/code/node_modules/babel-core/lib/transformation/file/options/option-manager.js:368:12)\n    at File.initOptions (/home/vagrant/code/node_modules/babel-core/lib/transformation/file/index.js:212:65)\n    at new File (/home/vagrant/code/node_modules/babel-core/lib/transformation/file/index.js:135:24)\n    at Pipeline.transform (/home/vagrant/code/node_modules/babel-core/lib/transformation/pipeline.js:46:16)\n    at transpile (/home/vagrant/code/node_modules/babel-loader/lib/index.js:50:20)\n    at /home/vagrant/code/node_modules/babel-loader/lib/fs-cache.js:118:18\n    at ReadFileContext.callback (/home/vagrant/code/node_modules/babel-loader/lib/fs-cache.js:31:21)\n    at FSReqWrap.readFileAfterOpen [as oncomplete] (fs.js:420:13)");

/***/ }),
/* 2 */
/***/ (function(module, exports) {

throw new Error("Module build failed: ModuleBuildError: Module build failed: Error: Missing binding /home/vagrant/code/node_modules/node-sass/vendor/linux-x64-57/binding.node\nNode Sass could not find a binding for your current environment: Linux 64-bit with Node.js 8.x\n\nFound bindings for the following environments:\n  - Windows 64-bit with Node.js 10.x\n\nThis usually happens because your environment has changed since running `npm install`.\nRun `npm rebuild node-sass` to download the binding for your current environment.\n    at module.exports (/home/vagrant/code/node_modules/node-sass/lib/binding.js:15:13)\n    at Object.<anonymous> (/home/vagrant/code/node_modules/node-sass/lib/index.js:14:35)\n    at Module._compile (module.js:653:30)\n    at Object.Module._extensions..js (module.js:664:10)\n    at Module.load (module.js:566:32)\n    at tryModuleLoad (module.js:506:12)\n    at Function.Module._load (module.js:498:3)\n    at Module.require (module.js:597:17)\n    at require (internal/module.js:11:18)\n    at Object.<anonymous> (/home/vagrant/code/node_modules/sass-loader/lib/loader.js:3:14)\n    at Module._compile (module.js:653:30)\n    at Object.Module._extensions..js (module.js:664:10)\n    at Module.load (module.js:566:32)\n    at tryModuleLoad (module.js:506:12)\n    at Function.Module._load (module.js:498:3)\n    at Module.require (module.js:597:17)\n    at require (internal/module.js:11:18)\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:13:17)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at runLoaders (/home/vagrant/code/node_modules/webpack/lib/NormalModule.js:195:19)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:364:11\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:170:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:27:11)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/home/vagrant/code/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/home/vagrant/code/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/home/vagrant/code/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/home/vagrant/code/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/home/vagrant/code/node_modules/webpack/lib/Compilation.js:157:10)\n    at moduleFactory.create (/home/vagrant/code/node_modules/webpack/lib/Compilation.js:460:10)\n    at factory (/home/vagrant/code/node_modules/webpack/lib/NormalModuleFactory.js:243:5)\n    at applyPluginsAsyncWaterfall (/home/vagrant/code/node_modules/webpack/lib/NormalModuleFactory.js:94:13)\n    at /home/vagrant/code/node_modules/tapable/lib/Tapable.js:268:11\n    at NormalModuleFactory.params.normalModuleFactory.plugin (/home/vagrant/code/node_modules/webpack/lib/CompatibilityPlugin.js:52:5)\n    at NormalModuleFactory.applyPluginsAsyncWaterfall (/home/vagrant/code/node_modules/tapable/lib/Tapable.js:272:13)\n    at resolver (/home/vagrant/code/node_modules/webpack/lib/NormalModuleFactory.js:69:10)\n    at process.nextTick (/home/vagrant/code/node_modules/webpack/lib/NormalModuleFactory.js:196:7)\n    at _combinedTickCallback (internal/process/next_tick.js:132:7)");

/***/ })
/******/ ]);