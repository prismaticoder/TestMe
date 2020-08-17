<template>
  <div>
        <v-tabs v-model="tab" :background-color="yellow" centered>
            <v-tab v-for="section in classes" :key="section.id" :href="`#${section.class.toLowerCase()}`">
                {{ section.class }}
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item v-for="section in classes" :key="section.id" :value="section.class.toLowerCase()">
                <table class="table table-bordered w-100 text-center">
                    <thead>
                        <th>S/N</th>
                        <th>EXAMINATION NUMBER</th>
                        <th>NAME</th>
                        <th colspan="2">OPTIONS</th>
                    </thead>
                    <tbody>
                        <tr v-for="(student,index) in section.students" :key="student.id">
                            <td>{{index+1}}</td>
                            <td>{{student.code}}</td>
                            <td>{{student.fullName}}</td>
                            <td>
                                <v-btn small text :color="yellow" title="Edit Student Details">
                                    <v-icon>
                                        mdi-pencil
                                    </v-icon>
                                </v-btn>
                            </td>
                            <td>
                                <v-btn small text :color="yellow" title="Delete Student Details">
                                    <v-icon>
                                        mdi-close
                                    </v-icon>
                                </v-btn>
                            </td>
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
