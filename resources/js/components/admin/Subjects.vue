<template>
  <div class="container">
      <v-expansion-panels accordion hover focusable>
          <v-expansion-panel class="col-md-4" v-for="subject in subjects" :key="subject.id">
                <v-expansion-panel-header><h5>{{subject.alias.toUpperCase()}}</h5></v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <SingleClass :single="single" :subject="subject" :yellow="yellow" v-for="single in classes" :key="single.id" />
                    </v-expansion-panel-content>
          </v-expansion-panel>
      </v-expansion-panels>
  </div>
</template>

<script>
import SingleClass from './SingleClass';
export default {
    name: "Subjects",
    props: ['subjects', 'classes'],
    data() {
        return {
            yellow: "#e67d23",
            dark: "#343a40"
        }
    },
    components: {
        SingleClass
    }
}
</script>

<style scoped>
.active {
    background-color: #343a40;
    color: #e67d23;
    border: solid #343a40 1px;
}
</style>
