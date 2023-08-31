window.onload = function(){

    let circularProgress= document.querySelector(".circular-progress");
    let progressValue= document.querySelector(".progress-value");

    let progressStartValue = 0;
    let progressEndValue = 100;
    let speed = 1000;

    let points = 0;
    for(let i = 0; i < question.length; i++){
        points = points + question[i].weight;
    }

    let progress = setInterval(() => {
        progressStartValue++;
        progressValue.textContent = `${progressStartValue}%`;
        circularProgress.style.background =`conic-gradient(#739a7e ${progressStartValue * 3.6}deg, #ededed 0deg)`;
        if(progressStartValue == progressEndValue){
            clearInterval(progress);
        }
        // console.log(progressStartValue);
    }, speed);

}
