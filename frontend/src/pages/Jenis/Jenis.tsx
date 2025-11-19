import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { FaPlus, FaEdit, FaTrash } from "react-icons/fa";

const dummyJenis = [
  { id: 1, type_name: "Berita Terkini", description: "berita-terkini" },
  { id: 2, type_name: "Berita Trending", description: "berita-trending" },
  { id: 3, type_name: "Berita Panas", description: "berita-panas" },
  { id: 4, type_name: "Topik Panas", description: "topik-panas" },
  { id: 5, type_name: "Dalam Negeri", description: "dalam-negeri" },
];

export default function Jenis() {
  const [jenis, setJenis] = useState(dummyJenis);
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
      setJenis(jenis.filter(cat => cat.id !== id));
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
          <h1 className="text-2xl font-bold text-white">Manage Tabel Jenis</h1>
          {/* Tombol Tambah */}
          <Link
            to="/create-type"
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
          <h3 className="text-lg font-semibold text-white">DataTable Kategori</h3>
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
                    Name Jenis
                  </th>
                  <th
                    scope="col"
                    className="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                  >
                    Deskripsi
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
                {jenis.map((jenis, index) => (
                  <tr key={jenis.id} className="hover:bg-gray-700">
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {index + 1}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-white">
                      {jenis.type_name}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                      {jenis.description}
                    </td>
                    <td className="px-4 py-3 whitespace-nowrap text-sm">
                      {/* Tombol Edit */}
                      <Link
                        to={`/edit-type`}
                        className="inline-flex items-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded mr-2 transition-colors duration-200"
                      >
                        <FaEdit className="text-lg"/>
                      </Link>
                      {/* Tombol Hapus */}
                      <button
                        onClick={() => handleDelete(jenis.id)}
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