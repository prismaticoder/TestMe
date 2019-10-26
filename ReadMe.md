## ToDo
1. Admin dashboard 

    - Contains a list of all subjects with links to host the exams, add questions or see exam results
    - Has links to view the students of each class (JSS1, JSS2 and JSS3)


2. Admin Login Page
3. Students Login Page
4. Single Page for Showing each question with their options (there's a "next" button for the user to choose the next question) (Link is `/exam/physics` for example)

    - This is the main SPA. The user has the question (with its options) occupying most of the page. Then there is a timer on one part of the page, a submit button, a section that shows the student's name and the subjects he is taking. Then a sidebar with links to each question, he clicks a question and the page automatically displays the question without refreshing, there's also a "next" button. The view for this is `exam.blade.php`

5. Page for viewing all students eligible to write an exam for a class (Link is `/admin/students/jss1` for example) It shows the user the list of all students. He can add a new student, delete a student or edit student details (all linking to modals, not new pages) The view for this is `admin/class-students.blade.php`
6. Page that Lists all subjects with links to host the exam and also to add question, also to view all results of students who took the question (This is the admin dashboard as referred above)
7. Admin Interface for adding all questions with summernote wysiwyg plugin (Okay. The link to this is `/admin/physics/1/questions` for jss1 questions example) This is where the admin adds new questions for a particular subject. The view is admin/questions view. The page has an editor for the main question. Below it are input fields for entering the options and below are radio buttons where the admin selects the correct option. There is also a sidebar like the student login page where the admin can click on a question he has added to either edit or remove it.
8. Submission Successful page for the student (A view after submission to indicate that the submission was successful, redirects back to the homepage afterwards)
9. Host Exam Page for the admin to be able to end exam abruptly and to see the number of submissions and the number of people doing the exam per class (Okay, see this as a modal. When the admin clicks 'host exam' on the dashboard, he then sees a modal that requests for the number of hours the exam will take, after that, he starts the exam, making the link accessible for every student. He can end the exam abruptly in the sense that he also has a timer on his page. And every student is to work by that timer, when his timer is done, he can choose to allow extra time for those who didn't login early or to end the exam immediately)
10. Page to show results of all students in a certain subject (Links from the homepage `admin/students/jss1/results` This is purely a pdf page)
11. The homepage. Page the student is redirected to on exam completion. Just a simple welcome page with links to admin login and student login
