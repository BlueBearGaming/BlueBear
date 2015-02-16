var Backoffice = {
    init: function () {
        // alert on item deletion
        $('.item-delete').on('click', function () {
            return confirm('It will be deleted!!! Are you really really sure ? 100% ?');
        });
        // init table sort
        console.log('trace ?');
        $('.table-sort').stupidtable();
    }
};

$(document).on('ready', function () {
    Backoffice.init();
});