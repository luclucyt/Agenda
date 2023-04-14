// $(document).ready(function() {
//      $('form').submit(function(e) {
//          e.preventDefault(); // Prevents the page from refreshing
//
//          let formData = $(this).serialize(); // Serialize the form data
//          alert(formData)
//
//          $.ajax({
//              type: 'POST',
//              url: '../PHP/index.php',
//              data: formData,
//              success: function(response) {
//                  // Handle the response from the server
// //                 alert("success")
// //                 alert(response)
//
//              },
//              error: function(xhr, status, error) {
//                  // Handle any errors that occur
//                  alert("error: " + error)
//              }
//          });
//      });
//  });
