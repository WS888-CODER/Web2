document.addEventListener("DOMContentLoaded", function() {
    // Show More / Show Less Functionality
    const showMoreButton = document.querySelector(".show-more");
    const showLessButton = document.querySelector(".show-less");
    const extraServices = document.querySelector(".extra-services");

    if (showMoreButton && showLessButton && extraServices) {
        showMoreButton.addEventListener("click", function () {
            extraServices.style.display = 'flex';
            showMoreButton.style.display = 'none';
            showLessButton.style.display = 'inline-block';
        });

        showLessButton.addEventListener("click", function () {
            extraServices.style.display = 'none';
            showMoreButton.style.display = 'inline-block';
            showLessButton.style.display = 'none';
        });
    }



    // Sign-Up Page Functionality
    const roleSelectionInputs = document.querySelectorAll("input[name='role']");
    const patientForm = document.getElementById("patient-form");
    const doctorForm = document.getElementById("doctor-form");

    function showForm(role) {
        patientForm.style.display = "none";
        doctorForm.style.display = "none";

        if (role === "patient") {
            patientForm.style.display = "block";
        } else if (role === "doctor") {
            doctorForm.style.display = "block";
        }
    }

    roleSelectionInputs.forEach(input => {
        input.addEventListener("change", function() {
            showForm(this.value);
        });
    });

    // Hide both forms initially
    showForm(null);

});
