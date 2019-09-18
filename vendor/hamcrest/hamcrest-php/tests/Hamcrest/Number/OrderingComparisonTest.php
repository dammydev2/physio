<div class="row">
    <div class="col-md-5">
        <div class="form-inline form-group">
            <label>Search:</label>
            <input v-model="searchFor" class="form-control" @keyup.enter="setFilter">
            <button class="btn btn-primary" @click="setFilter"><i class="glyphicon glyphicon-search"></i></button>
            <button class="btn btn-default" @click="resetFilter">Reset</button>
        </div>
    </div>
    <div class="col-md-7">
        <div class="dropdown form-inline pull-right">
            <label>Pagination Style:</label>
            <select class="form-control" v-model="paginationComponent">
                <option value="vuetable-pagination-simple">Simple</option>
                <option value="vuetable-pagination-bootstrap">Detail</option>
                <option value="vuetable-pagination-dropdown">Dropdown</option>
            </select>
            <label>Items per page:</label>
            <select class="form-control" v-model="perPage">
             