# vue-playlist

## Description

It is a personal project in which I'm experimenting with the vuejs framework with rest API in PHP.
## Vue Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).

## Php project setup

### Configure your apache path
```
PROJECT_DIR/rest/api
```

### Set your API domain in vue js
```
PROJECT_DIR/src/main.js
```
```js
Vue.prototype.$api_url = 'http://api.test/';
```

### Set your db connexion
```
PROJECT_DIR/rest/env.php
```
