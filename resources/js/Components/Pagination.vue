<template>
    <nav v-if="data.total > data.per_page" aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: !data.prev_page_url }">
          <a class="page-link" href="#" @click.prevent="changePage(data.current_page - 1)" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li v-for="page in pageRange" :key="page" class="page-item" :class="{ active: page === data.current_page }">
          <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: !data.next_page_url }">
          <a class="page-link" href="#" @click.prevent="changePage(data.current_page + 1)" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </template>

  <script>
  export default {
    props: {
      data: {
        type: Object,
        required: true
      }
    },
    emits: ['pagination-change-page'],
    computed: {
      pageRange() {
        const range = 2;
        const start = Math.max(1, this.data.current_page - range);
        const end = Math.min(this.data.last_page, this.data.current_page + range);
        return Array.from({ length: end - start + 1 }, (_, i) => start + i);
      }
    },
    methods: {
      changePage(page) {
        if (page >= 1 && page <= this.data.last_page) {
          this.$emit('pagination-change-page', page);
        }
      }
    }
  };
  </script>

  <style scoped>
  .pagination {
    display: flex;
    justify-content: center;
    list-style-type: none;
    padding: 0;
  }

  .page-item {
    margin: 0 5px;
  }

  .page-link {
    display: block;
    padding: 5px 10px;
    text-decoration: none;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
  }

  .page-item.active .page-link {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
  }

  .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dee2e6;
  }
  </style>
