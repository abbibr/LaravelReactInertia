import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput";
import TextAreaInput from "@/Components/TextAreaInput";
import TextInput from "@/Components/TextInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, useForm } from "@inertiajs/react";

export default function Edit({ auth, task, projects, users }) {
  const { data, setData, post, errors, reset } = useForm({
    image: "",
    image_path: task.image_path || "",
    name: task.name || "",
    status: task.status || "",
    description: task.description || "",
    due_date: task.due_date || "",
    project_id: task.project_id || "",
    user_id: task.assignedUser.id || "",
    priority: task.priority || "",
    _method: "PUT",
  });

  const onSubmit = (e) => {
    e.preventDefault();

    post(route("task.update", task.id));
  };

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <div className="flex justify-between items-center">
          <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Task: {task.name}
          </h2>
        </div>
      }
    >
      <Head title="Edit Task"></Head>

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <form
              onSubmit={onSubmit}
              className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >
              {task.image_path && (
                <div className="mb-4">
                  <img src={task.image_path} className="w-64" />
                </div>
              )}

              <div>
                <InputLabel value="Project"></InputLabel>

                <SelectInput
                  name="project_id"
                  className="mt-1 block w-full"
                  onChange={(e) => setData("project_id", e.target.value)}
                  defaultValue={data.project_id}
                >
                  <option value="">Select Project</option>

                  {projects.data.map((project, index) => (
                    <option value={project.id}>{project.name}</option>
                  ))}
                </SelectInput>

                <InputError
                  message={errors.project_id}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Image"></InputLabel>

                <TextInput
                  type="file"
                  name="image"
                  className="mt-1 block w-full"
                  onChange={(e) => setData("image", e.target.files[0])}
                ></TextInput>

                <InputError
                  message={errors.image}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Name"></InputLabel>

                <TextInput
                  type="text"
                  name="name"
                  value={data.name}
                  className="mt-1 block w-full"
                  isFocused={true}
                  onChange={(e) => setData("name", e.target.value)}
                ></TextInput>

                <InputError message={errors.name} className="mt-2"></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Description"></InputLabel>

                <TextAreaInput
                  id="task_description"
                  type="text"
                  name="description"
                  value={data.description}
                  className="mt-1 block w-full"
                  onChange={(e) => setData("description", e.target.value)}
                ></TextAreaInput>

                <InputError
                  message={errors.description}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Deadline"></InputLabel>

                <TextInput
                  type="date"
                  name="due_date"
                  value={data.due_date}
                  className="mt-1 block w-full"
                  isFocused={true}
                  onChange={(e) => setData("due_date", e.target.value)}
                ></TextInput>

                <InputError
                  message={errors.due_date}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Status"></InputLabel>

                <SelectInput
                  name="status"
                  className="mt-1 block w-full"
                  onChange={(e) => setData("status", e.target.value)}
                  defaultValue={data.status}
                >
                  <option value="">Select Status</option>
                  <option value="pending">Pending</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                </SelectInput>

                <InputError
                  message={errors.status}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Priority"></InputLabel>

                <SelectInput
                  name="priority"
                  className="mt-1 block w-full"
                  onChange={(e) => setData("priority", e.target.value)}
                  defaultValue={data.priority}
                >
                  <option value="">Select Priority</option>
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </SelectInput>

                <InputError
                  message={errors.priority}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="Task Assigned User"></InputLabel>

                <SelectInput
                  name="user_id"
                  className="mt-1 block w-full"
                  onChange={(e) => setData("user_id", e.target.value)}
                  defaultValue={data.user_id}
                >
                  <option value="">Select User</option>
                  {users.data.map((user, index) => (
                    <option value={user.id}>{user.name}</option>
                  ))}
                </SelectInput>

                <InputError
                  message={errors.user_id}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4 text-right">
                <Link
                  href={route("task.index")}
                  className="inline-block bg-gray-100 py-1 px-3 text-sm h-8 text-gray-800 rounded shadow transition-all hover:bg-gray-200 mr-2"
                >
                  Cancel
                </Link>

                <button className="bg-emerald-500 py-1 px-3 text-sm h-8 text-white rounded shadow transition-all hover:bg-emerald-600">
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
