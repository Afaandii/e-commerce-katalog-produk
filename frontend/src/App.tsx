import { BrowserRouter as Router, Routes, Route } from "react-router";
import NotFound from "./pages/OtherPage/NotFound";
import AppLayout from "./layout/AppLayout";
import { ScrollToTop } from "./components/common/ScrollToTop";
import Category from "./pages/Category/Category";
import CreateCategory from "./pages/Category/CreateCategory";
import EditCategory from "./pages/Category/EditCategory";
import Jenis from "./pages/Jenis/Jenis";
import CreateJenis from "./pages/Jenis/CreateJenis";
import EditJenis from "./pages/Jenis/EditJenis";
import Merk from "./pages/Merk/Merk";
import CreateMerk from "./pages/Merk/CreateMerk";
import EditMerk from "./pages/Merk/EditMerk";
import Produk from "./pages/Produk/Produk";
import CreateProduct from "./pages/Produk/CreateProduk";
import EditProduct from "./pages/Produk/EditProduk";
import ProdukImage from "./pages/ProdukImage/ProdukImage";
import CreateProdukImage from "./pages/ProdukImage/CreateProdukImage";
import EditProdukImage from "./pages/ProdukImage/EditProdukImage";
import SignIn from "./pages/AuthPages/SignIn";
import SignUp from "./pages/AuthPages/SignUp";
import Home from "./pages/Dashboard/Home";
import ProtectedRoute from "./components/protect/ProtectedRoute";

export default function App() {
  return (
    <>
      <Router>
        <ScrollToTop />
        <Routes>
          {/* Login Form And Register */}
          <Route index path="/" element={<SignIn />} />
          <Route path="/register" element={<SignUp />} />

          {/* Dashboard Layout */}
          <Route element={
            <ProtectedRoute>
              <AppLayout />
            </ProtectedRoute>
            }>
            <Route path="/dashboard" element={<Home />} />
            {/* Category Page */}
            <Route path="/category" element={<Category />} />
            <Route path="/create-category" element={<CreateCategory />} />
            <Route path="/edit-category" element={<EditCategory />} />

            {/* Jenis Page */}
            <Route path="/type" element={<Jenis />} />
            <Route path="/create-type" element={<CreateJenis />} />
            <Route path="/edit-type" element={<EditJenis />} />

            {/* Merk Page */}
            <Route path="/brand" element={<Merk />} />
            <Route path="/create-brand" element={<CreateMerk />} />
            <Route path="/edit-brand" element={<EditMerk />} />
            
            {/* Product Page */}
            <Route path="/product" element={<Produk />} />  
            <Route path="/create-product" element={<CreateProduct />} />
            <Route path="/edit-product" element={<EditProduct />} />

            {/* Product Image Page */}
            <Route path="/image-product" element={<ProdukImage />} />  
            <Route path="/create-image-product" element={<CreateProdukImage />} />
            <Route path="/edit-image-product" element={<EditProdukImage />} />
          </Route>

          {/* Fallback Route If Not Found Page */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </Router>
    </>
  );
}
