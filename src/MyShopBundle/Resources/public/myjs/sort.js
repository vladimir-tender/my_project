$(function () {
    $('select[name=show_count]').change(function () {
        //alert(this.value);
        //$.cookie("products_per_page", this.value);
        document.cookie = "products_per_page=" + this.value;
        //document.location = window.location.host;
        document.location = "/";
    });
    //TODO: sort by AND show count (4, 8) + paginate in Controller
});