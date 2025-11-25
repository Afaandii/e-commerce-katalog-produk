import { useRef } from 'react';
import {
  FaTag,
  FaWatchmanMonitoring,
  FaFileInvoiceDollar,
  FaShoppingCart,
  FaLaptop,
  FaFutbol,
  FaStore,
  FaCreditCard,
  FaTshirt,
  FaUser,
  FaBaby,
  FaSpa,
} from 'react-icons/fa';
import type { JSX } from 'react/jsx-runtime';

interface Category {
  id: number;
  name: string;
  icon: JSX.Element;
}

export default function CategoryProduct() {
  const scrollContainerRef = useRef<HTMLDivElement>(null);

  const categories: Category[] = [
    {
      id: 1,
      name: 'Diskon s.d 700rb',
      icon: <FaTag />,
    },
    {
      id: 2,
      name: 'Watch series 11',
      icon: <FaWatchmanMonitoring />,
    },
    {
      id: 3,
      name: 'Tagihan & Isi ulang',
      icon: <FaFileInvoiceDollar />,
    },
    {
      id: 4,
      name: 'Bliblimart',
      icon: <FaShoppingCart />,
    },
    {
      id: 5,
      name: 'Gadget & Elektronik',
      icon: <FaLaptop />,
    },
    {
      id: 6,
      name: 'Sport & Wellness',
      icon: <FaFutbol />,
    },
    {
      id: 7,
      name: 'Komisi 20%',
      icon: <FaStore />,
    },
    {
      id: 8,
      name: 'PayLater',
      icon: <FaCreditCard />,
    },
    {
      id: 9,
      name: 'Fashion Pria',
      icon: <FaTshirt />,
    },
    {
      id: 10,
      name: 'Fashion Wanita',
      icon: <FaUser />,
    },
    {
      id: 11,
      name: 'Ibu & Bayi',
      icon: <FaBaby />,
    },
    {
      id: 12,
      name: 'Kecantikan',
      icon: <FaSpa />,
    },
  ];

  return (
    <div className="relative w-full max-w-7xl mx-auto px-6 py-3">
      {/* Scrollable Container */}
      <div
        ref={scrollContainerRef}
        className="flex gap-2 overflow-x-auto scrollbar-hide"
        style={{
          scrollbarWidth: 'none',
          msOverflowStyle: 'none',
          WebkitOverflowScrolling: 'touch',
        }}
      >
        {categories.map((category) => (
          <div
            key={category.id}
            className="shrink-0 w-32 sm:w-36 md:w-40 cursor-pointer flex flex-col items-center justify-center h-36 p-2"
          >
            {/* Icon Container */}
            <div className="bg-gray-100 rounded-2xl p-4 flex items-center justify-center w-24 h-20">
              <div className="text-2xl">{category.icon}</div>
            </div>

            {/* Category Name - Centered & Fixed Height */}
            <p className="text-center text-sm font-medium text-gray-800 line-clamp-2 h-12 flex items-center justify-center px-1">
              {category.name}
            </p>
          </div>
        ))}
      </div>

      {/* CSS untuk menyembunyikan scrollbar */}
      <style>{`
        .scrollbar-hide::-webkit-scrollbar {
          display: none;
        }
      `}</style>
    </div>
  );
}