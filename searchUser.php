<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Search Report By Mobile No</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>

    <div class="container mt-5">
        <civ class="col-12">
            <h4>Search Mobile Number</h4>
        </civ>
        <div class="position-relative">
            <div class="input-group">
                <input type="number" class="form-control rounded" placeholder="Search" aria-label="Search"
                    aria-describedby="search-addon" min="10" max="10" id="searchInput"
                    oninput="handleChangeNumber(this)" onkeyup={handleSeachOnEnter(event)} />
                <button type="button" id="searchBtn" class="btn btn-primary" data-mdb-ripple-init>search</button>
            </div>
            <div id="emailHelp" class="form-text">You can search by Mobile Number.</div>
        </div>
        <div id="searchInputErr" class="form-text"></div>
    </div>

    <script>

        const handleChangeNumber = (input) => {
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10);
            }
            $("#searchInputErr").text("");
        }

        const handleSeachOnEnter = (e) => {
            if (e.key === "Enter") handleSearchData();
        }

        const handleSearchData = () => {
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
                                title: 'Student Report not found!',
                                text: '',
                            });
                        }
                    },
                    error: function (error) {
                        console.log("error: " + error);
                    }
                });

            }
        }

        $("#searchBtn").on("click", handleSearchData)
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>