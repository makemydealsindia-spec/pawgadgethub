/**
 * PawGadgetHub: tiny vanilla JS (no jQuery).
 * Mobile menu, search panel, header shadow.
 */
(function () {
	'use strict';

	var header = document.getElementById('masthead');
	var nav = document.getElementById('site-nav');
	var navBtn = document.querySelector('.js-nav-toggle');
	var searchBtn = document.querySelector('.js-search-toggle');
	var searchPanel = document.getElementById('header-search');

	function setNav(open) {
		if (!nav || !navBtn) { return; }
		nav.classList.toggle('is-open', open);
		navBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	function setSearch(open) {
		if (!searchPanel || !searchBtn) { return; }
		if (open) {
			searchPanel.removeAttribute('hidden');
			var input = searchPanel.querySelector('input[type="search"]');
			if (input) { input.focus(); }
		} else {
			searchPanel.setAttribute('hidden', '');
		}
		searchBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	if (navBtn) {
		navBtn.addEventListener('click', function () {
			setNav(navBtn.getAttribute('aria-expanded') !== 'true');
		});
	}

	if (searchBtn) {
		searchBtn.addEventListener('click', function () {
			var willOpen = searchBtn.getAttribute('aria-expanded') !== 'true';
			setSearch(willOpen);
			if (willOpen) { setNav(false); }
		});
	}

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			setNav(false);
			setSearch(false);
		}
	});

	// Close the mobile menu when a link inside it is tapped.
	if (nav) {
		nav.addEventListener('click', function (e) {
			if (e.target.closest && e.target.closest('a')) { setNav(false); }
		});
	}

	// Shadow under the sticky header once the page scrolls.
	if (header) {
		var ticking = false;
		var update = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 8);
			ticking = false;
		};
		window.addEventListener('scroll', function () {
			if (!ticking) {
				window.requestAnimationFrame(update);
				ticking = true;
			}
		}, { passive: true });
		update();
	}
})();
