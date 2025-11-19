import { Link } from "react-router-dom";
import Select from "../../components/form/Select";
import FileInput from "../../components/form/input/FileInput";

export default function CreateProdukImage() {
  const optionsProductImage = [
    { value: "elektronik", label: "Elektronik" },
    { value: "dapur", label: "Dapur" },
    { value: "ruang tamu", label: "Ruang Tamu" },
  ];
  const handleSelectChangeProductImage = (value: string | number) => {
    console.log("Selected value:", value);
  };

   const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      console.log("Selected file:", file.name);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Logika submit data ke API atau state
    // console.log("Submitting:", formData);
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
          <h1 className="text-2xl font-bold text-white">Form Tambah Product Image</h1>
        </div>
      </section>

      {/* Form Card */}
      <div className="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div className="p-6">
          <form onSubmit={handleSubmit}>
            {/* Product */}
            <div className="mb-4">
              <label htmlFor="product_id" className="block text-sm font-medium text-white mb-1">
                Product Id
              </label>
              <Select options={optionsProductImage} placeholder="Pilih Product" onChange={handleSelectChangeProductImage} id="product_id" name="product_id" />
            </div>

            {/* Image Field */}
            <div className="mb-6">
              <label
                htmlFor="slug"
                className="block text-sm font-medium text-white mb-1"
              >
                Image Product
              </label>
              <FileInput onChange={handleFileChange} className="custom-class" />
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
                to="/image-product"
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