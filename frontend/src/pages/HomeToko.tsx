import Carousel from "../components/ui-toko/Carousel";
import CategoryProduct from "../components/ui-toko/CategoryProduct";
import Navigation from "../components/ui-toko/Navigation";

export default function HomeToko(){
  return (
    <>
      {/* navigasi */}
      <Navigation />

      <main className="md:pt-[140px] pt-14 pb-16 bg-white min-h-screen">
        {/* carousel banner */}
        <Carousel />
        {/* carousel category */}
        <CategoryProduct />
      </main>
    </>
  )
}