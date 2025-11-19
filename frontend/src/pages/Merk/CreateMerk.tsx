import { useState } from "react";
import { Link } from "react-router-dom";

export default function CreateMerk() {
  const [formData, setFormData] = useState({
    brand_name: "",
    description: "",
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Logika submit data ke API atau state
    console.log("Submitting:", formData);
    // Contoh: setelah submit, redirect atau reset form
    // setFormData({ name: "", slug: "" });
    // history.push("/category"); // Jika menggunakan react-router v5
    // atau gunakan navigate dari react-router-dom v6
  };

  return (
    <>
      {/* Header Section */}
      <section className="mb-6">
        <div className="flex items-center justify-between p-3 rounded-t-lg">
          <h1 className="text-2xl font-bold text-white">Form Tambah Merk</h1>
        </div>
      </section>

      {/* Form Card */}
      <div className="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div className="p-6">
          <form onSubmit={handleSubmit}>
            {/* Nama Kategori Field */}
            <div className="mb-4">
              <label
                htmlFor="name"
                className="block text-sm font-medium text-white mb-1"
              >
                Nama Merk
              </label>
              <input
                type="text"
                id="name"
                name="name"
                value={formData.brand_name}
                onChange={handleChange}
                placeholder="Masukan nama kategori"
                className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            {/* Slug Field */}
            <div className="mb-6">
              <label
                htmlFor="slug"
                className="block text-sm font-medium text-white mb-1"
              >
                Deskripsi
              </label>
              <input
                type="text"
                id="slug"
                name="slug"
                value={formData.description}
                onChange={handleChange}
                placeholder="Masukan slug"
                className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            {/* Tombol Simpan dan Kembali */}
            <div className="flex justify-between">
              <button
                type="submit"
                className="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200"
              >
                Simpan
              </button>
              <Link
                to="/brand"
                className="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition-colors duration-200"
              >
                Kembali
              </Link>
            </div>
          </form>
        </div>
      </div>
    </>
  );
}