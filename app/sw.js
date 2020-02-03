/*
 * Scheduled Exams sw.js
 * Copyright 2020 maicol07 - All rights reserved.
 * Version 1.0 (06/01/2020)
 */

self.addEventListener('install', e => {
    console.log('PWA Service Worker installing.');
    let timeStamp = Date.now();
    e.waitUntil(caches.open('scheduled_exams_service_worker').then(cache => {
        return cache.addAll([
            'assets/img/edit.svg',
            'assets/img/loading.svg',
            'assets/img/logo.svg',
            'assets/img/logo_full.svg',
            'assets/img/plus.svg',
            'assets/img/user.svg',
            'assets/img/undraw/empty.svg',
            'assets/img/undraw/exams.svg',
            'assets/img/undraw/no_data.svg',
            'assets/css/mdi-outline/mdi-outline.css',
            'assets/css/mdi-outline/fonts/mdi-outline.eot',
            'assets/css/mdi-outline/fonts/mdi-outline.svg',
            'assets/css/mdi-outline/fonts/mdi-outline.ttf',
            'assets/css/mdi-outline/fonts/mdi-outline.woff'
        ]).then(() => self.skipWaiting());
    }))
});
self.addEventListener('activate', event => {
    console.log('PWA Service Worker activating.');
    event.waitUntil(self.clients.claim());
});
self.addEventListener('fetch', event => {
    event.respondWith(caches.match(event.request).then(response => {
        return response || fetch(event.request);
    }));
});