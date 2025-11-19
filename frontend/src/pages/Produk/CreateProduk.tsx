import { useState } from "react";
import { Link } from "react-router-dom";
import Select from "../../components/form/Select";
import TextArea from "../../components/form/input/TextArea";

export default function CreateProduct() {
   const optionsCategory = [
    { value: "elektronik", label: "Elektronik" },
    { value: "dapur", label: "Dapur" },
    { value: "ruang tamu", label: "Ruang Tamu" },
  ];
  const handleSelectChangeCategory = (value: string | number) => {
    console.log("Selected value:", value);
  };

  const [formData, setFormData] = useState({
    category_id: "",
    jenis_id: "",
    brand_id: "",
    product_name: "",
    price: "",
    stock: "",
    ratings: "",
    spesification_product: "",
    information_product: "",
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
          <h1 className="text-2xl font-bold text-white">Form Tambah Product</h1>
        </div>
      </section>

      {/* Form Card */}
      <div className="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div className="p-6">
          <form onSubmit={handleSubmit}>
            {/* Category */}
            <div className="mb-4">
              <label htmlFor="category_id" className="block text-sm font-medium text-white mb-1">
                Category
              </label>
              <Select options={optionsCategory} placeholder="Pilih Kategori" onChange={handleSelectChangeCategory} id="category_id" name="category_id" />
            </div>

            {/* Jenis */}
            <div className="mb-4">
              <label htmlFor="jenis_id" className="block text-sm font-medium text-white mb-1">
                Category
              </label>
              <Select options={optionsCategory} placeholder="Pilih Jenis" onChange={handleSelectChangeCategory} id="jenis_id" name="jenis_id" />
            </div>

            {/* Merk */}
            <div className="mb-4">
              <label htmlFor="merk_id" className="block text-sm font-medium text-white mb-1">
                Category
              </label>
              <Select options={optionsCategory} placeholder="Pilih Merk" onChange={handleSelectChangeCategory} id="merk_id" name="merk_id" />
            </div>

            {/* Nama Produk Field */}
            <div className="mb-4">
              <label
                htmlFor="name"
                className="block text-sm font-medium text-white mb-1"
              >
                Nama Produk
              </label>
              <input
                type="text"
                id="name"
                name="name"
                placeholder="Masukan nama produk"
                className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            {/* Price Field */}
            <div className="mb-4">
              <label
                htmlFor="price"
                className="block text-sm font-medium text-white mb-1"
              >
                Harga Produk
              </label>
              <input
                type="number"
                id="price"
                name="price"
                placeholder="Masukan harga produk"
                className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            {/* Stock Field */}
            <div className="mb-4">
              <label
                htmlFor="stock"
                className="block text-sm font-medium text-white mb-1"
              >
                Stock Produk
              </label>
              <input
                type="number"
                id="stock"
                name="stock"
                value={formData.product_name}
                onChange={handleChange}
                placeholder="Masukan stock produk"
                className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            {/* Ratings Field */}
            <div className="mb-4">
              <label
                htmlFor="rating"
                className="block text-sm font-medium text-white mb-1"
              >
                Ratings Produk
              </label>
              <input
                type="number"
                step={0.1}
                inputMode="decimal"
                id="stock"
                name="stock"
                placeholder="Masukan nama ratings, contoh: 4.5"
                className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
              />
            </div>

            {/* spesifikasi Field */}
            <div className="mb-6">
              <label
                htmlFor="spesfikasi_produk"
                className="block text-sm font-medium text-white mb-1"
              >
                Spesifikasi Produk
              </label>
              <TextArea 
              rows={6} 
              placeholder="Masukan Spesifikasi Produk" />
            </div>

            {/* informasi Field */}
            <div className="mb-6">
              <label
                htmlFor="slug"
                className="block text-sm font-medium text-white mb-1"
              >
                Informasi Produk
              </label>
              <TextArea
              rows={6} 
              placeholder="Masukan Informasi Produk" />
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
                to="/product"
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