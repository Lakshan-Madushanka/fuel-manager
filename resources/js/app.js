import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 *Alert progress functionality
 */

    window.loadAlertProgressIndicator =() => {
        const currentMillis = Date.now()
        const timeOutTime = 5000;
        const indicateLoader =
            setInterval(function () {
                let diff = Date.now() - currentMillis
                let percentage = 100 - ((diff / timeOutTime) * 100)
                $('#alert-loading-indicator').css('width', percentage + '%')
            }, 1)

        setTimeout(function () {
            clearInterval(indicateLoader)
            $('#alert-1').remove();
        }, timeOutTime + 250)
    }

/**
 * end of alert progress functionality
 */