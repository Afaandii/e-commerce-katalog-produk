import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { FaPlus, FaEdit, FaTrash } from "react-icons/fa";

const dummyProduct = [
  { id: 1, product_name: "Berita Terkini", category_id:1, type_id: 1, brand_id:1, price:50000, stock: 100, ratings: 4.5, spesification_product: "berita-terkini", information_product: "berita-terkini" },
  { id: 2, product_name: "Berita Trending", category_id:2, type_id:2, brand_id:2, price:50000, stock: 100, ratings: 4.5, spesification_product: "berita-trending", information_product: "berita-terkini" },
  { id: 3, product_name: "Berita Panas", category_id:3, type_id:3, brand_id:3, price:50000, stock: 100, ratings: 4.5, spesification_product: "berita-panas", information_product: "berita-terkini" },
  { id: 4, product_name: "Topik Panas", category_id:4, type_id:4, brand_id:4, price:50000, stock: 100, ratings: 4.5, spesification_product: "topik-panas", information_product: "berita-terkini" },
  { id: 5, product_name: "Dalam Negeri", category_id:5, type_id:5, brand_id:5, price:50000, stock: 100, ratings: 4.5, spesification_product: "dalam-negeri", information_product: "berita-terkini" },
];

export default function Produk() {
  const [product, setProduct] = useState(dummyProduct);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  // Simulasi pesan sukses (ganti dengan logika Anda)
  useEffect(() => {
    // Contoh: jika ada pesan sukses dari route sebelumnya
    // const msg = getSuccessMessageFromURL(); // Implementasi Anda
    // if (msg) setSuccessMessage(msg);
  }, []);

  // Fungsi untuk menghapus kategori (simulasi)
  const handleDelete = (id: number) => {
    if (window.confirm("Anda Yakin Mau Hapus Data?")) {
      setProduct(product.filter(cat => cat.id !== id));
      setSuccessMessage("Kategori berhasil dihapus.");
      // Tampilkan pesan sukses selama beberapa detik
      setTimeout(() => setSuccessMessage(null), 3000);
    }
  };

  return (
    <>
      {/* Header Section */}
      <section className="mb-6">
        <div className="flex items-center justify-between p-3 rounded-t-lg">
          <h1 className="text-2xl font-bold text-white">Manage Tabel Product</h1>
          {/* Tombol Tambah */}
          <Link
            to="/create-product"
            className="inline-flex items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200"
          >
            <FaPlus className="text-lg" />
          </Link>
        </div>
      </section>

      {/* Card Container */}
      <div className="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        {/* Card Header */}
        <div className="px-4 py-3 bg-gray-700 border-b border-gray-600">
          <h3 className="text-lg font-semibold text-white">DataTable Product</h3>
        </div>

        {/* Card Body - Table */}
        <div className="p-4">
          {/* Pesan Sukses (Alert) */}
          {successMessage && (
            <div className="mb-4 p-3 bg-green-600 text-white rounded-md flex items-center justify-between">
              <span>{successMessage}</span>
              <button
                onClick={() => setSuccessMessage(null)}
                className="ml-2 text-white hover:text-gray-200 focus:outline-none"
              >
                &times;
              </button>
            </div>
          )}

          {/* Tabel */}
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-600">
              <thead className="bg-gray-900">
                <tr>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    No
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Category_id
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Jenis_id
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Merk_id
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Nama Produk
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Price
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Stock
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Ratings
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Spesifikasi Produk
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Informasi Produk
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody className="bg-gray-800 divide-y divide-gray-600">
                {product.map((product, index) => (
                  <tr key={product.id} className="hover:bg-gray-700">
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {index + 1}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.category_id}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.type_id}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.brand_id}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.product_name}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.price}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.stock}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {product.ratings}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                      {product.spesification_product}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                      {product.information_product}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm">
                      {/* Tombol Edit */}
                      <Link
                        to={`/edit-product`}
                        className="inline-flex items-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded mr-2 transition-colors duration-200"
                      >
                        <FaEdit className="text-lg"/>
                      </Link>
                      {/* Tombol Hapus */}
                      <button
                        onClick={() => handleDelete(product.id)}
                        className="inline-flex items-center px-4 py-3 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200"
                      >
                        <FaTrash className="text-lg" />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </>
  );
}