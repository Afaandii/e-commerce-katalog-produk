import axios from "axios";
import { useCallback, useEffect, useState } from "react";
import {
  FiSearch,
  FiShoppingCart,
  FiDownload,
  FiGrid,
  FiHome,
  FiUser,
} from "react-icons/fi";

export default function Navigation() {
  const [searchQuery, setSearchQuery] = useState("");
  const [cartCount, setCartCount] = useState(0);

  const getToken = () => {
    return localStorage.getItem("token") || sessionStorage.getItem("token");
  };

  const fetchCartCount = useCallback(async () => {
    try {
      const token = getToken();

      if (!token) {
        setCartCount(0);
        return;
      }

      const res = await axios.get("http://localhost:8000/api/v1/cart-product", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const totalItems = res.data?.data?.total_items ?? 0;
      setCartCount(totalItems);
    } catch (error) {
      console.error("Gagal mengambil cart:", error);
      setCartCount(0);
    }
  }, []);

  useEffect(() => {
    const loadCart = async () => {
      await fetchCartCount();
    };

    loadCart();
  }, [fetchCartCount]);

  return (
    <>
      {/* Desktop Navigation (fixed saat discroll) */}
      <div className="hidden md:block fixed top-0 left-0 right-0 z-50">
        <div className="bg-green-600 text-white px-6 py-1">
          <div className="max-w-7xl mx-auto flex items-center justify-between text-sm">
            <div className="flex items-center gap-6">
              <button className="flex items-center gap-2 hover:opacity-80">
                <FiDownload size={16} />
                <span>Download Aplikasi Goshop</span>
              </button>
            </div>
          </div>
        </div>

        <div className="bg-green-600 text-white px-6 py-2">
          <div className="max-w-7xl mx-auto flex items-center gap-6">
            {/* Logo */}
            <div className="flex items-center gap-2">
              <a href="/">
                <img
                  src="/images/backend-logo.png"
                  alt="Goshop"
                  className="w-36 h-10"
                />
              </a>
            </div>

            {/* Search Bar */}
            <div className="flex-1 max-w-3xl">
              <div className="relative flex items-center rounded-lg overflow-hidden bg-white border border-blue-200">
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  placeholder="Search produk"
                  className="flex-1 px-4 py-2 text-gray-700 placeholder-gray-500 focus:outline-none rounded-l-lg"
                />
                <button className="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 mr-1 rounded-md transition-colors">
                  <FiSearch size={24} />
                </button>
              </div>
            </div>

            {/* Cart and Auth Buttons */}
            <div className="flex items-center gap-4">
              <a href="/cart-produk" className="relative hover:opacity-80 ml-2">
                <FiShoppingCart size={28} />
                {cartCount > 0 && (
                  <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                    {cartCount}
                  </span>
                )}
                <span
                  className="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold 
                          rounded-full w-5 h-5 flex items-center justify-center"
                >
                  {cartCount}
                </span>
              </a>
              <div className="w-0.5 bg-white h-8 mx-3"></div>
              <a
                href="/login"
                className="px-6 py-1 border-2 font-medium border-white rounded-full hover:bg-blue-500 hover:text-white hover:border-blue-500 transition-colors"
              >
                Masuk
              </a>
              <a
                href="/register"
                className="px-6 py-1 bg-white font-medium text-green-600 rounded-full hover:bg-gray-100 transition-colors"
              >
                Daftar
              </a>
            </div>
          </div>
        </div>

        <div className="bg-green-600 text-white px-6 py-1">
          <div className="max-w-7xl mx-auto flex items-center justify-between">
            <div className="flex items-center gap-6">
              <button className="flex items-center gap-2 hover:opacity-80">
                <FiGrid size={20} />
                <span className="font-medium">Kategori</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Mobile Navigation (md ke bawah) */}
      <div className="md:hidden">
        {/* Top Fixed Bar */}
        <div className="fixed top-0 left-0 right-0 bg-green-600 text-white px-4 py-3 z-50">
          <div className="flex items-center justify-between gap-2">
            {/* Logo */}
            <div className="shrink-0">
              <img
                src="/images/backend-logo.png"
                alt="Goshop"
                className="w-25 h-8 mr-2"
              />
            </div>

            {/* Search Bar - Mengisi ruang */}
            <div className="flex-1 max-w-xs">
              <div className="relative flex items-center rounded-lg overflow-hidden bg-white">
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  placeholder="Cari produk"
                  className="flex-1 px-3 py-1.5 text-gray-700 text-sm placeholder-gray-500 focus:outline-none"
                />
                <button className="bg-green-600 text-white p-1.5 mr-1 rounded-full">
                  <FiSearch size={16} />
                </button>
              </div>
            </div>

            {/* Tombol Masuk */}
            <a
              href="/login"
              className="ml-2 text-sm font-medium px-3 py-1.5 border-2 border-white rounded-full whitespace-nowrap"
            >
              Masuk
            </a>
          </div>
        </div>

        {/* Bottom Fixed Bar */}
        <div className="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around items-center py-2 z-50">
          <a href="/" className="flex flex-col items-center text-gray-600">
            <FiHome size={24} />
            <span className="text-xs mt-1">Home</span>
          </a>
          <a
            href="/cart-produk"
            className="flex flex-col items-center text-gray-600 relative"
          >
            <FiShoppingCart size={24} />
            {cartCount > 0 && (
              <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {cartCount}
              </span>
            )}
            <span className="text-xs mt-1">Keranjang</span>
          </a>
          <button className="flex flex-col items-center text-gray-600">
            <FiUser size={24} />
            <span className="text-xs mt-1">Akun</span>
          </button>
        </div>
      </div>
    </>
  );
}
