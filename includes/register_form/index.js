(function ($) {
    $(document).ready(function () {
        //國際電話號碼
        const phoneInputField = document.querySelector("#billing_phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["hk"],
            //onlyCountries: ["tw", "us", "gb"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
        //init ITI TEL
        phoneInput.setCountry("hk");
    });
})(jQuery);
