import { Link } from "react-router";
import UserInfoCard from "./UserInfoCard";
import { FaArrowAltCircleLeft } from "react-icons/fa";

export default function UserProfiles() {
  return (
    <>
      <div className="w-screen h-screen border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div className="mb-4">
          <Link
            to="/"
            className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-gray-900 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 transition-colors"
          >
            <FaArrowAltCircleLeft className="size-5" />
             Dashboard
          </Link>
        </div>
        <div className="space-y-6">
          <UserInfoCard />
        </div>
      </div>
    </>
  );
}
