@extends('document::layouts.master')

@section('content')
@include('document::layouts.header.header')
<div>
    <div class="menu-mobile">
        <button class="menu-mobile-toggle">Menu</button>
        <ul class="menu-mobile-list"></ul>
    </div>
    <div class="sidebar">
        <div class="menu">
        </div>
    </div>
    @include('document::api.component.content')
</div>
<script>
    var h1Elements = document.querySelectorAll('h1');
    var menu = document.querySelector('.menu');
    var clickedElement = '';
    h1Elements.forEach((h1Element, index) => {
        var menuItem = document.createElement('a');
        menuItem.className = 'menu-item';
        menuItem.href = '#';
        menuItem.textContent = h1Element.textContent;

        menuItem.addEventListener('click', (event) => {
            event.preventDefault();
            clickedElement = menuItem;
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    clickedElement = '';
                    observer.disconnect();
                }
            });

            observer.observe(h1Element);
            window.scroll({
                behavior: 'auto',
                left: 0,
                top: h1Element.offsetTop - 100
            });
            document.querySelectorAll('.menu-item').forEach(menuItem => menuItem.classList.remove('active'));
            menuItem.classList.add('active');
        });
        menu.appendChild(menuItem);
    });
    var menuMobile = document.querySelector('.menu-mobile');
    var menuMobileToggle = document.querySelector('.menu-mobile-toggle');
    var menuMobileList = document.querySelector('.menu-mobile-list');

    h1Elements.forEach((h1Element, index) => {
        var menuItemMobile = document.createElement('li');
        var menuItemMobileLink = document.createElement('a');
        menuItemMobileLink.className = 'menu-item-mobile';
        menuItemMobileLink.href = '#';
        menuItemMobileLink.textContent = h1Element.textContent;
        menuItemMobile.addEventListener('click', (event) => {
            event.preventDefault();
            var clickedElement = event.target;
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    clickedElement = '';
                    observer.disconnect();
                }
            });

            observer.observe(h1Element);
            window.scroll({
                behavior: 'smooth',
                left: 0,
                top: h1Element.offsetTop
            });

        });
        menuItemMobile.appendChild(menuItemMobileLink);
        menuMobileList.appendChild(menuItemMobile);
    });

    menuMobileToggle.addEventListener('click', () => {
        document.querySelector('.menu-mobile-list').classList.toggle('menu-mobile-open');
    });

    var copyButtons = document.querySelectorAll('.copy-button');
    copyButtons.forEach((copyButton) => {
        copyButton.addEventListener('click', (event) => {
            var codeBlock = copyButton.parentNode;
            var preElement = codeBlock.querySelector('pre');
            var code = preElement.textContent;
            var textarea = document.createElement('textarea');
            textarea.value = code;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            copyButton.textContent = 'copied';
            setTimeout(() => {
                copyButton.textContent = 'copy';
            }, 1500);
        });
    });

    window.addEventListener('scroll', () => {
        if (window.scrollY <= 100) {
            highlightTopH1();
        } else if (clickedElement != '') {
            document.querySelectorAll('.menu-item').forEach(menuItem => menuItem.classList.remove('active'));
            clickedElement.classList.add('active');
        } else {
            h1Elements.forEach((h1Element, index) => {
                var menuItem = menu.children[index];
                if (isElementInView(h1Element)) {
                    document.querySelectorAll('.menu-item').forEach(menuItem => menuItem.classList.remove('active'));
                    menuItem.classList.add('active');
                }
            });
        }
    });

    function isElementInView(element) {
        var rect = element.getBoundingClientRect();
        var viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        var topThreshold = viewportHeight * 0;
        var bottomThreshold = viewportHeight * 0.45;
        return (
            rect.top >= topThreshold &&
            rect.bottom <= bottomThreshold
        );
    }

    function highlightTopH1() {
        var topH1 = h1Elements[0];
        var topMenuItem = menu.children[0];
        document.querySelectorAll('.menu-item').forEach(menuItem => menuItem.classList.remove('active'));
        topMenuItem.classList.add('active');
    }
    window.history.scrollRestoration = 'manual';
    window.onload = function () {
        menu.firstElementChild.classList.add('active');
    };
</script>
@endsection