
<div class="row">
    <div class="col-sm-12 col-lg-8">
        <div class="card">
            <div class="card-header p-3">
                <h4 class="card-title">User Registrations</h4>
            </div>

            <div class="card-body p-2" style="display: block;">
                <div class="table-responsive" style="max-height: 250px;">

                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Position</th>
                                <th>Email Address</th>
                                <th>Mobile Number</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(count($registered_user) > 0){ 
                                foreach($registered_user as $user){
                                    echo '<tr data-link="'. route("admin.users.edit", [$user->id]).'">';
                                        echo '<td>' . $user->name . '</td>';
                                        echo '<td>' . $user->position_arr->name . '</td>';
                                        echo '<td>' . $user->company_arr->name . '</td>';
                                        echo '<td>' . $user->email . '</td>';
                                        echo '<td>' . $user->mobile_number . '</td>';
                                        echo '<td></td>';
                                    echo '</tr>';
                                }
                            }else{
                                echo '<tr>';
                                    echo '<td colspan="5">No recent user registrations</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
            </div>

        </div>
    </div>
</div>