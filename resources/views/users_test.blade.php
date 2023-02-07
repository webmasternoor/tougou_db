<!DOCTYPE html>
<html>
<head>
   <title>How to Dynamically load content in Bootstrap modal - Laravel 9</title>

   <!-- Meta -->
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <meta charset="utf-8">

   <!-- CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" >

   <!-- Script -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" ></script>

</head>
<body>
   <div class='container'>

      <!-- Modal -->
      <div class="modal fade" id="empModal" >
         <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title">Employee Info</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                   <table class="w-100" id="tblempinfo">
                      <tbody></tbody>
                   </table>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>

      <!-- Employees List -->
      <table class="table mt-5" border='1' id='empTable' style='border-collapse: collapse;'>
         <thead>
            <tr>
               <th>S.no</th>
               <th>Username</th>
               <th>Name</th>
               <th>&nbsp;</th>
            </tr>
         </thead>
         <tbody>
         @php
         print_r($employees);
         $sno = 0;
         @endphp
         @foreach($employees as $employee)
            <tr>
              <td>{{ ++$sno }}</td>
              <td>{{ $employee->mgt_no }}</td>
              <td>{{ $employee->family_name }}</td>
              <td><button class='btn btn-info viewdetails' data-id='{{ $employee->id }}' >View Details</button></td>
            </tr>
         @endforeach
         </tbody>
      </table>

   </div>

   <!-- Script -->
   <script type='text/javascript'>
   $(document).ready(function(){

      $('#empTable').on('click','.viewdetails',function(){
          var empid = $(this).attr('data-id');

          if(empid > 0){

             // AJAX request
             var url = "{{ route('getEmployeeDetails',[':empid']) }}";
             url = url.replace(':empid',empid);

             // Empty modal data
             $('#tblempinfo tbody').empty();

             $.ajax({
                 url: url,
                 dataType: 'json',
                 success: function(response){

                     // Add employee details
                     $('#tblempinfo tbody').html(response.html);

                     // Display Modal
                     $('#empModal').modal('show');
                 }
             });
          }
      });

   });
   </script>
</body>
</html>
