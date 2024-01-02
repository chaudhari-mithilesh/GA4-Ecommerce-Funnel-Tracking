jQuery(document).ready(function () {
    /**
     * Pushes data to the Google Tag Manager data layer.
     *
     * This function takes variable arguments and pushes them to the Google Tag Manager
     * data layer using the `dataLayer.push` method. It also logs a message to the console
     * indicating that the form tracking data has been pushed to the data layer.
     *
     * @since 1.0.0
     * @param {...*} args The arguments to be pushed to the data layer.
     * @return {void}
     */
    var gtag = function () {
        dataLayer.push(arguments);
        console.log('form_tracking data has been pushed to dataLayer');
    };
    console.log('ga4 functions defined');
});