import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput";
import TextAreaInput from "@/Components/TextAreaInput";
import TextInput from "@/Components/TextInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, useForm } from "@inertiajs/react";

export default function Edit({ auth, user }) {
  const { data, setData, post, errors, reset } = useForm({
    name: user.name || "",
    email: user.email || "",
    password: "",
    password_confirmation: "",
    _method: "PUT",
  });

  const onSubmit = (e) => {
    e.preventDefault();

    post(route("user.update", user.id));
  };

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <div className="flex justify-between items-center">
          <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit User: {user.name}
          </h2>
        </div>
      }
    >
      <Head title="Edit User"></Head>

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <form
              onSubmit={onSubmit}
              className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >

              <div className="mt-4">
                <InputLabel value="User Name"></InputLabel>

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
                <InputLabel value="User Email"></InputLabel>

                <TextInput
                  type="email"
                  name="email"
                  value={data.email}
                  className="mt-1 block w-full"
                  isFocused={true}
                  onChange={(e) => setData("email", e.target.value)}
                ></TextInput>

                <InputError
                  message={errors.email}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="User Password"></InputLabel>

                <TextInput
                  type="password"
                  name="password"
                  value={data.password}
                  className="mt-1 block w-full"
                  isFocused={true}
                  onChange={(e) => setData("password", e.target.value)}
                ></TextInput>

                <InputError
                  message={errors.password}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4">
                <InputLabel value="User Password Confirmation"></InputLabel>

                <TextInput
                  type="password"
                  name="password_confirmation"
                  value={data.password_confirmation}
                  className="mt-1 block w-full"
                  isFocused={true}
                  onChange={(e) =>
                    setData("password_confirmation", e.target.value)
                  }
                ></TextInput>

                <InputError
                  message={errors.password_confirmation}
                  className="mt-2"
                ></InputError>
              </div>

              <div className="mt-4 text-right">
                <Link
                  href={route("user.index")}
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
