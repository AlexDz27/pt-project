<template>
  <section class="hero">
    <header>
      <nav class="app__nav navbar navbar-light py-4 bg-pur">
        <div class="container justify-content-end">
          <button class="btn btn-outline-primary">Sign in</button>
        </div>
      </nav>
    </header>

    <section class="d-flex justify-content-center">
      <form @submit.prevent="log" class="property-search app__property-search">
        <input class="form-control mb-2" name="location" placeholder="Enter location" required>
        <input class="form-control mb-2" name="start-date" type="date">
        <input class="form-control mb-2" name="end-date" type="date">
        <label class="text-white" for="bedroom">Select type of the bedroom:</label>
        <select class="form-select mb-2" name="bedroom" id="bedroom">
          <option value="one">1</option>
          <option value="two">2</option>
          <option value="three">3</option>
        </select>
        <br>
        <button class="btn btn-success w-100" type="submit">Search</button>
      </form>
    </section>

    <div class="call-to-action hero__call-to-action container">
      <h1 class="text-7xl">
        <span class="call-to-action__part bg-purple-400">Discover rooms,</span><br>
        <span class="call-to-action__part bg-indigo-400">hotels & make assets! 💰</span>
      </h1>
    </div>
  </section>
</template>

<script>
export default {
}
</script>
