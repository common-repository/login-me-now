/**
 * Used when Cross-Origin-Opener-Policy blocked the access to the opener. We can't have a reference of the opened windows, so we should attempt to refresh only the windows that has opened popups.
 */
window._lmnHasOpenedPopup = true;
window._lmnWebViewNoticeElement = null;
let widthPopup = '600';
let heightPopup = '600';
let scriptOptions = {}

window.LMNPopup = function (url, title, w, h) {
    const userAgent = navigator.userAgent,
        mobile = function () {
            return /\b(iPhone|iP[ao]d)/.test(userAgent) ||
                /\b(iP[ao]d)/.test(userAgent) ||
                /Android/i.test(userAgent) ||
                /Mobile/i.test(userAgent);
        },
        screenX = window.screenX !== undefined ? window.screenX : window.screenLeft,
        screenY = window.screenY !== undefined ? window.screenY : window.screenTop,
        outerWidth = window.outerWidth !== undefined ? window.outerWidth : document.documentElement.clientWidth,
        outerHeight = window.outerHeight !== undefined ? window.outerHeight : document.documentElement.clientHeight - 22,
        targetWidth = mobile() ? null : w,
        targetHeight = mobile() ? null : h,
        left = parseInt(screenX + (outerWidth - targetWidth) / 2, 10),
        right = parseInt(screenY + (outerHeight - targetHeight) / 2.5, 10),
        features = [];
    if (targetWidth !== null) {
        features.push('width=' + targetWidth);
    }
    if (targetHeight !== null) {
        features.push('height=' + targetHeight);
    }
    features.push('left=' + left);
    features.push('top=' + right);
    features.push('scrollbars=1');

    const newWindow = window.open(url, title, features.join(','));

    if (window.focus) {
        newWindow.focus();
    }

    window._lmnHasOpenedPopup = true;

    return newWindow;
};

let isWebView = null;

function checkWebView() {
    if (isWebView === null) {
        function _detectOS(ua) {
            if (/Android/.test(ua)) {
                return "Android";
            } else if (/iPhone|iPad|iPod/.test(ua)) {
                return "iOS";
            } else if (/Windows/.test(ua)) {
                return "Windows";
            } else if (/Mac OS X/.test(ua)) {
                return "Mac";
            } else if (/CrOS/.test(ua)) {
                return "Chrome OS";
            } else if (/Firefox/.test(ua)) {
                return "Firefox OS";
            }
            return "";
        }

        function _detectBrowser(ua) {
            let android = /Android/.test(ua);

            if (/Opera Mini/.test(ua) || / OPR/.test(ua) || / OPT/.test(ua)) {
                return "Opera";
            } else if (/CriOS/.test(ua)) {
                return "Chrome for iOS";
            } else if (/Edge/.test(ua)) {
                return "Edge";
            } else if (android && /Silk\//.test(ua)) {
                return "Silk";
            } else if (/Chrome/.test(ua)) {
                return "Chrome";
            } else if (/Firefox/.test(ua)) {
                return "Firefox";
            } else if (android) {
                return "AOSP";
            } else if (/MSIE|Trident/.test(ua)) {
                return "IE";
            } else if (/Safari\//.test(ua)) {
                return "Safari";
            } else if (/AppleWebKit/.test(ua)) {
                return "WebKit";
            }
            return "";
        }

        function _detectBrowserVersion(ua, browser) {
            if (browser === "Opera") {
                return /Opera Mini/.test(ua) ? _getVersion(ua, "Opera Mini/") :
                    / OPR/.test(ua) ? _getVersion(ua, " OPR/") :
                        _getVersion(ua, " OPT/");
            } else if (browser === "Chrome for iOS") {
                return _getVersion(ua, "CriOS/");
            } else if (browser === "Edge") {
                return _getVersion(ua, "Edge/");
            } else if (browser === "Chrome") {
                return _getVersion(ua, "Chrome/");
            } else if (browser === "Firefox") {
                return _getVersion(ua, "Firefox/");
            } else if (browser === "Silk") {
                return _getVersion(ua, "Silk/");
            } else if (browser === "AOSP") {
                return _getVersion(ua, "Version/");
            } else if (browser === "IE") {
                return /IEMobile/.test(ua) ? _getVersion(ua, "IEMobile/") :
                    /MSIE/.test(ua) ? _getVersion(ua, "MSIE ")
                        :
                        _getVersion(ua, "rv:");
            } else if (browser === "Safari") {
                return _getVersion(ua, "Version/");
            } else if (browser === "WebKit") {
                return _getVersion(ua, "WebKit/");
            }
            return "0.0.0";
        }

        function _getVersion(ua, token) {
            try {
                return _normalizeSemverString(ua.split(token)[1].trim().split(/[^\w\.]/)[0]);
            } catch (o_O) {
            }
            return "0.0.0";
        }

        function _normalizeSemverString(version) {
            const ary = version.split(/[\._]/);
            return (parseInt(ary[0], 10) || 0) + "." +
                (parseInt(ary[1], 10) || 0) + "." +
                (parseInt(ary[2], 10) || 0);
        }

        function _isWebView(ua, os, browser, version, options) {
            switch (os + browser) {
                case "iOSSafari":
                    return false;
                case "iOSWebKit":
                    return _isWebView_iOS(options);
                case "AndroidAOSP":
                    return false;
                case "AndroidChrome":
                    return parseFloat(version) >= 42 ? /; wv/.test(ua) : /\d{2}\.0\.0/.test(version) ? true : _isWebView_Android(options);
            }
            return false;
        }

        function _isWebView_iOS(options) {
            const document = (window["document"] || {});

            if ("WEB_VIEW" in options) {
                return options["WEB_VIEW"];
            }
            return !("fullscreenEnabled" in document || "webkitFullscreenEnabled" in document || false);
        }

        function _isWebView_Android(options) {
            if ("WEB_VIEW" in options) {
                return options["WEB_VIEW"];
            }
            return !("requestFileSystem" in window || "webkitRequestFileSystem" in window || false);
        }

        const options = {},
            nav = window.navigator || {},
            ua = nav.userAgent || "",
            os = _detectOS(ua),
            browser = _detectBrowser(ua),
            browserVersion = _detectBrowserVersion(ua, browser);

        isWebView = _isWebView(ua, os, browser, browserVersion, options);
    }

    return isWebView;
}

function isAllowedWebViewForUserAgent(provider) {
    const facebookAllowedWebViews = [
        'Instagram',
        'FBAV',
        'FBAN'
    ];
    let whitelist = [];

    if (provider && provider === 'facebook') {
        whitelist = facebookAllowedWebViews;
    }

    const nav = window.navigator || {},
        ua = nav.userAgent || "";

    if (whitelist.length && ua.match(new RegExp(whitelist.join('|')))) {
        return true;
    }

    return false;
}

function disableButtonInWebView(providerButtonElement) {
    if (providerButtonElement) {
        providerButtonElement.classList.add('lmn-disabled-provider');
        providerButtonElement.setAttribute('href', '#');

        providerButtonElement.addEventListener('pointerdown', (e) => {
            if (!window._lmnWebViewNoticeElement) {
                window._lmnWebViewNoticeElement = document.createElement('div');
                window._lmnWebViewNoticeElement.id = "lmn-notices-fallback";
                window._lmnWebViewNoticeElement.addEventListener('pointerdown', function (e) {
                    this.parentNode.removeChild(this);
                    window._lmnWebViewNoticeElement = null;
                });
                const webviewNoticeHTML = '<div class="error"><p>' + scriptOptions._localizedStrings.webview_notification_text + '</p></div>';

                window._lmnWebViewNoticeElement.insertAdjacentHTML("afterbegin", webviewNoticeHTML);
                document.body.appendChild(window._lmnWebViewNoticeElement);
            }
        });
    }

}

function lmnInit(className, apiKey = '') {
    const buttons = document.querySelectorAll(className);
    if (buttons.length && checkWebView()) {
        buttons.forEach(function (button) {
            if (scriptOptions._unsupportedWebviewBehavior === 'disable-button') {
                disableButtonInWebView(button);
            } else {
                button.remove();
                buttonCountChanged = true;
            }
        });
    }
}


window._lmnDOMReady = function (callback) {
	if ( document.readyState === "complete" || document.readyState === "interactive" ) {
		callback();
	} else {
		document.addEventListener( "DOMContentLoaded", callback );
	}
};

window._lmnDOMReady(function () {
    window.lmnRedirect = function (url) {
        const overlay = document.createElement('div');
        overlay.id = "lmn-redirect-overlay";
        let overlayHTML = '';
        
        const overlayContainer = "<div id='lmn-redirect-overlay-container'>",
            overlayContainerClose = "</div>",
            overlaySpinner = "<div id='lmn-redirect-overlay-spinner'></div>";

        overlayHTML = overlayContainer + overlaySpinner + overlayContainerClose;

        overlay.insertAdjacentHTML("afterbegin", overlayHTML);
        document.body.appendChild(overlay);
        
        window.location = url;
    };

    let targetWindow = scriptOptions._targetWindow || 'prefer-popup'
    let lastPopup = false;

    const buttonLinks = document.querySelectorAll(' a[data-action="lmn-connect"]');
    buttonLinks.forEach(function (buttonLink) {
        buttonLink.addEventListener('click', function (e) {
            
            if (lastPopup && !lastPopup.closed) {
                e.preventDefault();
                lastPopup.focus();
            } else {

                let href = this.href,
                    success = false;
                if (href.indexOf('?') !== -1) {
                    href += '&';
                } else {
                    href += '?';
                }

                const redirectTo = '';
                if (redirectTo === 'current') {
                    href += 'redirect=' + encodeURIComponent(window.location.href) + '&';
                } else if (redirectTo && redirectTo !== '') {
                    href += 'redirect=' + encodeURIComponent(redirectTo) + '&';
                }

                if (targetWindow !== 'prefer-same-window' && checkWebView()) {
                    targetWindow = 'prefer-same-window';
                }

                if (targetWindow === 'prefer-popup') {
                    lastPopup = LMNPopup(href + 'display=popup', 'lmn-social-connect', widthPopup, heightPopup);
                    if (lastPopup) {
                        success = true;
                        e.preventDefault();
                    }
                } else if (targetWindow === 'prefer-new-tab') {
                    const newTab = window.open(href + 'display=popup', '_blank');
                    if (newTab) {
                        if (window.focus) {
                            newTab.focus();
                        }
                        success = true;
                        window._lmnHasOpenedPopup = true;
                        e.preventDefault();
                    }
                }

                if (!success) {
                    window.location = href;
                    e.preventDefault();
                }
            }
        });
    });
    
    lmnInit('.lmn_google_login_button',);
    lmnInit('.lmn_facebook_login_button');
});

/**
 * Cross-Origin-Opener-Policy blocked the access to the opener
 */
if (typeof BroadcastChannel === "function") {
    const _lmnLoginBroadCastChannel = new BroadcastChannel('lmn_login_broadcast_channel');
    _lmnLoginBroadCastChannel.onmessage = (event) => {
        if (window?._lmnHasOpenedPopup && event.data?.action === 'reload') {
            window._lmnHasOpenedPopup = false;
            
            const url = event.data?.href;
            _lmnLoginBroadCastChannel.close();
            if (typeof window.lmnRedirect === 'function') {
                window.lmnRedirect(url);
            } else {
                window.opener.location = url;
            }
        }
    };
}