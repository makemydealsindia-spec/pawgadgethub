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

	// Interactive "Browse by Pet" filter tabs on the homepage.
	var petTabs = document.querySelectorAll('.pgh-pet-tab');
	var petCards = document.querySelectorAll('#reviews .pgh-card');
	function filterByPet(pet) {
		if (!petTabs.length || !petCards.length) { return; }
		petTabs.forEach(function (btn) {
			var active = btn.getAttribute('data-filter') === pet;
			btn.classList.toggle('is-active', active);
			btn.setAttribute('aria-pressed', active ? 'true' : 'false');
		});
		petCards.forEach(function (card) {
			var type = card.getAttribute('data-pet') || 'both';
			var show = pet === 'all' || type === 'both' || type === pet;
			card.style.display = show ? '' : 'none';
		});
	}
	petTabs.forEach(function (btn) {
		btn.addEventListener('click', function () {
			filterByPet(btn.getAttribute('data-filter'));
		});
	});
	document.querySelectorAll('[data-jump-pet]').forEach(function (link) {
		link.addEventListener('click', function (e) {
			var pet = e.currentTarget.getAttribute('data-jump-pet');
			if (pet) { filterByPet(pet); }
		});
	});
})();
