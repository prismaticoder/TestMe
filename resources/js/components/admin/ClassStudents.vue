<template>
  <div>
        <v-tabs v-model="tab" :background-color="yellow" centered>
            <v-tab v-for="section in classes" :key="section.id">
                {{ section.class }}
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item v-for="section in classes" :key="section.id">
                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>EXAMINATION NUMBER</th>
                        <th>NAME</th>
                        <th>OPTIONS</th>
                    </thead>
                    <tbody>
                        <tr v-for="(student,index) in section.students" :key="student.id">
                            <td>{{index+1}}</td>
                            <td>{{student.code}}</td>
                            <td>{{student.fullName}}</td>
                            <td>Edit</td>
                        </tr>
                    </tbody>
                </table>
            </v-tab-item>
        </v-tabs-items>
  </div>
</template>

<script>
export default {
    name: "ClassStudents",
    props: ['classes'],
    data() {
        return {
            tab: null,
            yellow:  "#e67d23",
        }
    }
}
</script>

<style>

</style>
