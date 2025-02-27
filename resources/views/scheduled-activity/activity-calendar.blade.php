<style>
    #preloader-row .custom-loader {
        /* Start Center Items on Table */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 10px;
        /* End Center Items on Table */
        width: 50px;
        height: 12px;
        background:
            radial-gradient(circle closest-side, #0170A8 90%, #0000) 0% 50%,
            radial-gradient(circle closest-side, #0170A8 90%, #0000) 50% 50%,
            radial-gradient(circle closest-side, #0170A8 90%, #0000) 100% 50%;
        background-size: calc(100%/3) 100%;
        background-repeat: no-repeat;
        animation: d7 1s infinite linear;
    }

    @keyframes d7 {
        33% {
            background-size: calc(100%/3) 0%, calc(100%/3) 100%, calc(100%/3) 100%
        }

        50% {
            background-size: calc(100%/3) 100%, calc(100%/3) 0%, calc(100%/3) 100%
        }

        66% {
            background-size: calc(100%/3) 100%, calc(100%/3) 100%, calc(100%/3) 0%
        }
    }
</style>

<div id="calendar"></div>