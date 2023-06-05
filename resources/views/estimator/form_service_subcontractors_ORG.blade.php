
<!-- begin row -->

<form method="POST" action="#" accept-charset="UTF-8" id="subcontractor_form" class="admin-form">
<div class="row">
    <div class="col-sm-7">

        <select class="form-control btn-outline-success" name="posubVendorID" id="posubVendorID">
            <option value="0">Select a Sub Contractor</option>
            <option value="713">(DO NOT USE) A. Lewis Milling  - 0%</option>
            <option value="691">(DO NOT USE) Alexis Luis Milling  - 0%</option>
            <option value="690">Alexis Luis - 12%</option>
            <option value="1545">Allied Trucking - 10%</option>
            <option value="714">Asphalt Specialist  - 0%</option>
            <option value="712">Barriero  - 0%</option>
            <option value="790">BRIAN  SNYDER - 0%</option>
            <option value="433">Charlie Blackburn - 0%</option>
            <option value="1571">COASTAL PIPELINE - 0%</option>
            <option value="1401">Danelo Moreno - 30%</option>
            <option value="692">Daren Daly - 0%</option>
            <option value="1572">DOUGLASS, LEAVY &amp; ASSOCIATES INC.  - 0%</option>
            <option value="715">GEM Paver Systems  - 0%</option>
            <option value="718">Green Earth Power Washing  - 0%</option>
            <option value="919">Justin Lieffer - 0%</option>
            <option value="720">Native Lines  - 0%</option>
            <option value="974">Paul Garcia - 30%</option>
            <option value="716">Perfect Pavers  - 0%</option>
            <option value="717">Rck's Bricks  - 0%</option>
            <option value="1593">Rockline Underground - 0%</option>
            <option value="721">Scott Munroe  - 0%</option>
            <option value="719">SP Facility Management  - 0%</option>
        </select>

    </div>
</div>
<br>
<!-- begin row -->
<div class="row">
    <div class="col-sm-7">
        <label class="control-label">Description of Service</label>
        <textarea class="form-control" name="description" id="description"></textarea>
    </div>
</div>
<br>
<!-- begin row -->
<div class="row">
    <div class="col-sm-3">
        <label class="control-label">Over Head %</label>
        <input type="text" class="form-control btn-outline-success" name="overHead" id="overHead">
    </div>
    <div class="col-sm-3">
        <label class="control-label">Quoted Cost</label>
        <input type="text" class="form-control btn-outline-success" name="cost" id="cost">
    </div>
    <div class="col-sm-3">
        <label class="control-label">Have Bid</label>
        <input type="checkbox" class="form-control btn-outline-success" name="have_bid" id="have_bid" value="1">
    </div>
</div>
<!-- end row -->

<br>

<!-- begin row -->
<div class="row">
    <div class="col-sm-2">
        Sub Contractor
    </div>
    <div class="col-sm-3 left">
        Description
    </div>
    <div class="col-sm-2">
        Over head
    </div>
    <div class="col-sm-2">
        Cost
    </div>
    <div class="col-sm-2">
        Total
    </div>
    <div class="col-sm-1">
        &nbsp;
    </div>

</div>

<!-- begin row -->
<div class="row">

    <div class="col-sm-2">
        Asphalt Specialist
        <br>0%
    </div>

    <div class="col-sm-3">
        1 mob
    </div>

    <div class="col-sm-2">
        $0.00
    </div>

    <div class="col-sm-2">
        $2,000.00
    </div>

    <div class="col-sm-2">

        $2,000.00
    </div>

    <div class="col-sm-1">
        <a href="#"><span class="ri-delete-bin-2-line"></span> remove</a>
    </div>


</div>

<div class="row">
    <div class="col-sm-3">
        <a href="#" class="{{ $site_button_class }}">Add Sub Contractor</a>
    </div>
    <div class="col-sm-2">
        <label class="control-label"></label> <span class="lbl">Total Sub Contractors</span>
    </div>

    <div class="col-sm-3">
        <input type="hidden" id="SubTotal" name="SubTotal" value="8645.00">
        <input type="text" id="SubTotals" name="SubTotals" class="form-control" style="background:lightblue;" value="$8,645.00" disabled="">
    </div>
</div>
</form>
