$(document).ready(function () {
    (function () {
        jQuery(function () {
            this.film_rolls || (this.film_rolls = []);
            this.film_rolls['slider-gallery'] = new FilmRoll({
                container: '#slider-gallery',
                height: 560
            });
            return true;
        });
    }).call(this);
})
