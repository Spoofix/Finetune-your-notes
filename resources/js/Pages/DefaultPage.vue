<script setup>
    import {Link} from "@inertiajs/vue3"
</script>


<template>
  <div class="error-container">
    <div>
      <img src="../../../public/assets/systemImages/404.png" alt="404 Image" class="error-image">
      <br>
      <h1 class="h1">404 Not Found</h1>
      <p>Sorry, the page you are looking for does not exist.</p>
        <Link href="/dashboard"  class="w-16 py-1 mx-auto transition-all duration-150 ease-linear bg-yellow-100 mt-9 tableButton focus:outline-none">Go back to Desktop</Link>
    </div>
  </div>
</template>

<style scoped>
p{
    margin-bottom: 20px;
}
.tableButton{
display: inline-flex;
height: 42px;
padding: 10px 16px;
justify-content: center;
align-items: center;
gap: 21px;
flex-shrink: 0;
border-radius: 30px;
background: var(--Yellow-yellow-300, #FFDD54);
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
width: fit-content;
color: black;
cursor: pointer;
font: bolder;
}
.error-container {
    margin-top: 90px;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 90%;
}

div {
  text-align: center;
  align-content: center;
}

.error-image {
  max-width: 260px;
  height: auto;
  margin: auto;
}

</style>
