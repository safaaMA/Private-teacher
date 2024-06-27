function Activite() {
    var menu = document.getElementById('menu');
    menu.classList.toggle('Activ');
    menu.classList.toggle('display');
}
function AddStudent() {
    var studentForm = document.getElementById('studentForm');
    studentForm.classList.toggle('display');
}
document.querySelector('.close').addEventListener('click', function () {
    var studentForm = document.getElementById('studentForm');
    studentForm.classList.remove('display');
});

document.querySelector('.closeHw').addEventListener('click', function () {
    var studentForm = document.getElementById('HWForm');
    studentForm.classList.remove('display');
});


document.querySelector('.closeTime').addEventListener('click', function () {
    var studentForm = document.getElementById('TimeForm');
    studentForm.classList.remove('display');
});



function HWForm() {
    var studentForm = document.getElementById('HWForm');
    studentForm.classList.toggle('display');
}
function TimeForm() {
    var studentForm = document.getElementById('TimeForm');
    studentForm.classList.toggle('display');
}

function generateCode() {
    event.preventDefault();

    const randomNumber = Math.floor(Math.random() * 401) + 100;

    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    const day = ('0' + currentDate.getDate()).slice(-2);
    const hours = ('0' + currentDate.getHours()).slice(-2);
    const minutes = ('0' + currentDate.getMinutes()).slice(-2);
    const seconds = ('0' + currentDate.getSeconds()).slice(-2);

    const formattedDate = `${year}${month}${day}${hours}${minutes}${seconds}`;

    const generatedCode = `${randomNumber}${formattedDate}`;

    document.getElementById('code').value = generatedCode;
    document.getElementById('generateButton').disabled = true;

}