const startBtn = document.querySelector('.btn1')
const popupInfo = document.querySelector('.popup-info')
const exitBtn = document.querySelector('.exit-btn')
const container = document.querySelector('.home')
const header = document.querySelector('.header')

startBtn.onclick = () =>{
    popupInfo.classList.add('active');
    container.classList.add('active');
    header.classList.add('active');
}
exitBtn.onclick = () =>{
    popupInfo.classList.remove('active');
    container.classList.remove('active');
    header.classList.remove('active');
}