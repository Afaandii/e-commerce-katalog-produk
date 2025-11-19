import { BrowserRouter as Router, Routes, Route } from "react-router";
import NotFound from "./pages/OtherPage/NotFound";
import AppLayout from "./layout/AppLayout";
import { ScrollToTop } from "./components/common/ScrollToTop";
import Home from "./pages/Dashboard/Home";
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

export default function App() {
  return (
    <>
      <Router>
        <ScrollToTop />
        <Routes>
          {/* Dashboard Layout */}
          <Route element={<AppLayout />}>
            <Route index path="/" element={<Home />} />

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

            {/* Others Page */}
            {/* <Route path="/profile" element={<UserProfiles />} /> */}
            {/* <Route path="/calendar" element={<Calendar />} /> */}
            {/* <Route path="/blank" element={<Blank />} /> */}

            {/* Forms */}
            {/* <Route path="/form-elements" element={<FormElements />} /> */}

            {/* Tables */}
            {/* <Route path="/basic-tables" element={<BasicTables />} /> */}

            {/* Ui Elements */}
            {/* <Route path="/alerts" element={<Alerts />} />
            <Route path="/avatars" element={<Avatars />} />
            <Route path="/badge" element={<Badges />} />
            <Route path="/buttons" element={<Buttons />} />
            <Route path="/images" element={<Images />} />
            <Route path="/videos" element={<Videos />} /> */}

            {/* Charts */}
            {/* <Route path="/line-chart" element={<LineChart />} />
            <Route path="/bar-chart" element={<BarChart />} /> */}
          </Route>

          {/* Auth Layout */}
          {/* <Route path="/signin" element={<SignIn />} />
          <Route path="/signup" element={<SignUp />} /> */}

          {/* Fallback Route */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </Router>
    </>
  );
}
