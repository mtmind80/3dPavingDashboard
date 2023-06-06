@push('footer')
    <script>

        $(document).ready(function () {

            alert('yes');
        var combinedcost = parseFloat(current_subContractor_total).toFixed(2);
        
        
        alert(combinedcost);
        
        //+ parseFloat($('#estimator_form_equipment_total_cost').val()) + parseFloat($('#estimator_form_labor_total_cost').val()) + parseFloat($('#estimator_form_additional_cost_total_cost').val()) + parseFloat($('#estimator_form_vehicle_total_cost').val()) + parseFloat($("#cost_per_day").val());
        var othercost = parseFloat($('#form_header_over_head').val()) + parseFloat($('#form_header_break_even').val()) + parseFloat($('#form_header_profit').val());
        console.log('combined ' + combinedcost);
        console.log('other: ' + othercost);
        headerElCombinedCosting.text('$' + combinedcost);

    });    
</script>
@endpush
