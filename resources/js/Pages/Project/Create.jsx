import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import {TextInput, TextAreaInput} from "@/Components/TextInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";

export default function Create({ auth }) {
  const { data, setData, post, errors, reset } = useForm({
    image: "",
    name: "",
    status: "",
    description: "",
    due_date: "",
  });

  const onSubmit = (e) => {
    e.preventDefault();

    post(route("project.create"));
  };

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <div className="flex justify-between items-center">
          <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Project
          </h2>
        </div>
      }
    >
      <Head title="Create Project"></Head>

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <form
              onSubmit={onSubmit}
              className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
              action=""
              method="post"
            >

              <div>
                <InputLabel
                  value="Project Image"
                ></InputLabel>

                <TextInput
                  type="file"
                  name="image"
                  value={data.image}
                  className="mt-1 block w-full"
                  onChanged={(e) => setData("image", e.target.value)}
                ></TextInput>

                <InputError
                  message={errors.image}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel
                  value="Project Name"
                ></InputLabel>

                <TextInput
                  type="text"
                  name="name"
                  value={data.name}
                  className="mt-1 block w-full"
                  isFocused={true}
                  onChanged={(e) => setData("name", e.target.value)}
                ></TextInput>

                <InputError
                  message={errors.name}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel
                  value="Project Description"
                ></InputLabel>

                <TextAreaInput
                  type="text"
                  name="description"
                  value={data.description}
                  className="mt-1 block w-full"
                  onChanged={(e) => setData("description", e.target.value)}
                ></TextAreaInput>

                <InputError
                  message={errors.description}
                  className="mt-2"
                ></InputError>
              </div>

            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
