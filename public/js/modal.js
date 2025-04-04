const myModal = document.getElementById('authentication-modal');


document.addEventListener('DOMContentLoaded', function () {


    myModal.classList.remove('hidden');
});

document.getElementById('closeModal').addEventListener('click', function() {

    myModal.classList.add('hidden');
});