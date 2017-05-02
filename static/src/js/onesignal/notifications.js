var OneSignal = window.OneSignal || [];
OneSignal.push(['init', {
    appId:
        window.onesignal.appId,
    autoRegister:
        false,
    subdomainName:
        window.onesignal.subdomainName,

    safari_web_id:
        window.onesignal.safariWebId,

    persistNotification:
        false,

    welcomeNotification: {
        title:
            'ФІСФМ',
        message:
            'Дякуємо за підписку!',
    },

    promptOptions: {
        autoAcceptTitle:
            'Click Allow',
        siteName:
            'ФІСФМ',
        actionMessage:
            'Ми хотіли б показати вам повідомлення про останні новини та оновлення.',
        acceptButtonText:
            'Дозволити',
        cancelButtonText:
            'Ні, дякую',
        exampleNotificationCaption:
            "",
        showCredit:
            false,
        exampleNotificationTitleDesktop:
            'ФІСФМ',
        exampleNotificationMessageDesktop:
            'Факультет інформаційних систем, фізики та математики',
        exampleNotificationTitleMobile:
            'ФІСФМ',
        exampleNotificationMessageMobile:
            'Факультет інформаційних систем, фізики та математики',
    },

    notifyButton: {
       enable: false,
    },

}]);

OneSignal.push(function() {
    OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (!isEnabled) {
            OneSignal.showHttpPrompt();
        }
    });
});

$('.js-subscribe-to-all').on('click', function () {
    OneSignal.push(['registerForPushNotifications']);
});
$('.js-subscribe-to-tag').on('click', function () {
    var tagName = $(this).data('tag-name');
    OneSignal.sendTag(tagName, 'ok');
});
$('.js-unsubscribe-to-tag').on('click', function () {
    var tagName = $(this).data('tag-name');
    OneSignal.deleteTag(tagName);
});
