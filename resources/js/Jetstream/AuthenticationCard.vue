<template>
    <!-- Screen -->
    <div class="h-screen flex bg-red-100">
        
<!-- Card Login kiri-->
    <!-- <div class="flex w-full xl:w-1/2 lg:w-1/2 bg-white justify-around items-center flex-col"> -->
    <div class="flex w-full bg-white justify-around items-center flex-col">
       <!-- Navbar responsive md sm -->
        <!-- <div class="bg-black w-full h-32 flex items-center justify-center xl:hidden lg:hidden" style="margin-top: -34px;"><img class="w-[90px]" src="../../../bootstrap/Logo-white.png" alt=""></div> -->
        <!-- <div class="flex-col flex items-center justify-center mt-[35px]"><img class="w-[90px]" src="../../../Logo-black.png" alt=""> <h1 class="ml-5 text-2xl font-bold "><span style="font-family: taviraj;">E-</span>Ticketing</h1></div> -->
        <div class="flex-col flex items-center justify-center mt-[35px]"><img class="w-[90px]" src="../../../public/assets/4.png" alt=""> <h1 class="ml-5 text-2xl font-bold "><span style="font-family: taviraj;">E-</span>Ticketing</h1></div>
        <!-- Sign in  -->
        <div class="">
            <div class="text-white  flex-col mb-8">
                <h1 class="text-center flex items-center justify-center mb-5" style="color: #000;font-family: Taviraj;font-size: 30px;font-style: normal;font-weight: 500;line-height: normal;">Sign In</h1>
            <p class="text-center" style="color: #7B7B7B;text-align: center;font-family: Taviraj;font-size: 20px;font-style: normal;font-weight: 500;line-height: normal;">Sistem Pelaporan Aplikasi Kimia Farma <br> Plant Banjaran</p>
        </div>
            <!-- Logo -->
            <div class=""> 
                <slot name="logo" /><slot />
                 </div>            
        </div>
        <div class="w-full ml-14 "><p>2023 - Tim IT Plant Banjaran</p></div>
    </div>
    <!-- card kanan -->
    <!-- <div class="flex w-1/2 justify-center items-center bg-black hidden xl:block lg:block">
        <div class="h-full flex-col bg-black">
        <div class="flex-col flex items-center justify-center text-white mt-[65px]"><img class="w-[90px]" src="../../../public/assets/3.png" alt=""> <h1 class="ml-5 text-2xl font-bold "><span style="font-family: taviraj;">E-</span>Ticketing</h1></div>
        <div class="w-full flex items-center justify-center mt-[35px]"><img class="w-3/4" src="../../../bootstrap/Image-Right.png" alt=""></div>
        <h1 class="text-center flex items-center justify-center mb-5 text-white mt-[45px]" style="font-family: Taviraj;font-size: 30px;font-style: normal;font-weight: 500;line-height: normal;">Laporkan Masalah Anda Terkait System Di E-Ticketing</h1>
        <div class="w-full flex items-center justify-center"><p class="text-white text-center w-3/4">Sistem ini dibuat untuk membantu permasalahan yang ada di website kimia farma plant Banjaran</p>
        </div></div>

    </div> -->
    
    </div>

</template>
