<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Bootstrap 5 Search with Dropdown</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>

    <div class="container mt-5">
        <div class="position-relative">
            <div class="input-group">
                <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                    aria-describedby="search-addon" id="searchInput" />
                <button type="button" id="searchBtn" class="btn btn-primary" data-mdb-ripple-init>search</button>
            </div>
        </div>
        <div id="searchInputErr" class="form-text"></div>
    </div>

    <script>

        $("#searchBtn").on("click", function (e) {
            const mobileNo = $("#searchInput").val();
            if (!mobileNo || mobileNo === "" || mobileNo.length === 0) {
                $("#searchInputErr").text("Please enter mobile number");
                $("#searchInputErr").css("color", "red");
            } else if (!/^[0-9]{10}$/.test(mobileNo)) {
                $("#searchInputErr").text("Please enter valid mobile number");
                $("#searchInputErr").css("color", "red");
            } else {
                $("#searchInputErr").text("");
                $.ajax({
                    url: "query.php",
                    type: "GET",
                    dataType: "JSON",
                    data: { searchInput: 1, mobileNo: mobileNo },
                    success: function (data) {
                        if (data.userid !== 0) {
                            console.log(data)
                            // var encodedData = encodeURIComponent(data.userid);
                            var url = `index.php?userReport=${data.useridencode}`;

                            // Navigate to the other PHP page
                            window.location.href = url;
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Stuent Report not found!',
                                text: '',
                            });
                        }
                        console.log("query.php", data)
                    },
                    error: function (error) {
                        console.log("error: " + error);
                    }
                });

            }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>