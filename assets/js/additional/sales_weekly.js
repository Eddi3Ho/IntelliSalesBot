$(document).ready(function() {
    var t = $("#table_weekly_sales_list").DataTable({
        //make table responsive
        "bAutoWidth": false,
        "columnDefs": [{
                "width": "10%",
                "targets": [1]
            },
            {
                "width": "5%",
                "targets": [0]
            },
            {
                "searchable": false,
                "targets": 0
            }
        ]
    });

    t.on('order.dt search.dt', function() {
        t.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();


}); // end of ready function

//function will trigger when user click on the view button
function view_sale(sale_id) {

    $.ajax({
        url: base_url + "sales/sales/view_sale",
        method: "POST",
        data: {
            sale_id: sale_id
        },
        success: function(data) {
            $('#view_sale_model').html(data);

        }
    });
}

//trigger function when the date input is changed
$('.date').change(function() {
    var start_date = document.getElementById("start_date").value;
    var end_date = document.getElementById("end_date").value;
    window.location.href = base_url + "sales/sales/weekly_sales_list/" + start_date + "/" + end_date;
});