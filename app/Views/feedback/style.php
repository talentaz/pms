
<style>
    .view-container{
        width: 640px;
    }
    .quiz-title {
        color: #000;
        margin-bottom: 30px;
    }
    .question{
        background: #fff;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .options{
        position: relative;
        padding-left: 40px;
    }
    #options label{
        display: block;
        margin-bottom: 15px;
        font-size: 14px;
        cursor: pointer;
    }
    .options input{
        opacity: 0;
    }
    .checkmark {
        position: absolute;
        top: -1px;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 50%;
    }
    .options input:checked ~ .checkmark:after {
        display: block;
    }
    .options .checkmark:after{
        content: "";
        width: 10px;
        height: 10px;
        display: block;
        background: white;
        position: absolute;
        top: 50%;
        left: 50%;
        border-radius: 50%;
        transform: translate(-50%,-50%) scale(0);
        transition: 300ms ease-in-out 0s;
    }
    .options input[type="radio"]:checked ~ .checkmark, .options input[type="checkbox"]:checked ~ .checkmark {
        background: #21bf73;
        transition: 300ms ease-in-out 0s;
    }
    .options input[type="radio"]:checked ~ .checkmark:after, .options input[type="checkbox"]:checked ~ .checkmark:after {
        transform: translate(-50%,-50%) scale(1);
    }

    @media(max-width:576px){
        .view-container{
            width: 100%;
        }
        .question{
            width: 100%;
            word-spacing: 2px;
        } 
    }
</style>
