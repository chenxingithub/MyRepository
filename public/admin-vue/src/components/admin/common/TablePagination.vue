<template>
  <nav class="text-center">
    <ul class="pagination">
      <li :class="isOnFirstPage ? disabledClass : ''">
        <a @click="loadPage('prev')"><i class="paginate_button previous">Previous</i></a>
      </li>

      <template v-if="notEnoughPages">
        <template v-for="n in totalPage">
          <li :class="isCurrentPage(n) ? 'paginate_button active' : 'paginate_button'">
            <a @click="loadPage(n)"> {{ n }} </a>
          </li>
        </template>
      </template>

      <template v-else>
        <template v-for="n in windowSize">
          <li :class="isCurrentPage(windowStart+n) ? 'paginate_button active' : 'paginate_button'">
            <a @click="loadPage(windowStart+n)">{{ windowStart+n }}</a>
          </li>
        </template>
      </template>

      <li :class="isOnLastPage ? disabledClass : ''">
        <a @click="loadPage('next')"><i class="paginate_button next">Next</i></a>
      </li>
    </ul>
  </nav>
</template>

<script>
import PaginationMixin from './TablePaginationMixin.vue'

export default {
  mixins: [PaginationMixin],
  methods: {
    loadPage(page) {
      this.$emit('loadPage', page)
    }
  }
}
</script>

<style lang="sass" scoped>
.active {
  background-color: #3d4e60;
  border-right: none;
}
.pagination li {
  cursor: pointer
}
</style>
