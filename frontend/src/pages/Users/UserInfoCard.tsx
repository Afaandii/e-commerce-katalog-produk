import { useState, useEffect } from "react";
import { useModal } from "../../hooks/useModal";
import { Modal } from "../../components/ui/modal";
import Button from "../../components/ui/button/Button";
import Input from "../../components/form/input/InputField";
import Label from "../../components/form/Label";
import { FaPencilAlt } from "react-icons/fa";
import axios from "axios";
import FileInput from "../../components/form/input/FileInput";

export default function UserInfoCard() {
  const { isOpen, openModal, closeModal } = useModal();

  const [userData, setUserData] = useState({
    name: "",
    email: "",
    profile_image: "",
  });

  const getToken = () => {
    return localStorage.getItem("token") || sessionStorage.getItem("token");
  };

  // State untuk loading dan error
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  // State untuk form edit
  const [editFormData, setEditFormData] = useState({
    name: "",
    email: "",
    password: "",
    profile_image: null as File | null,
  });

  useEffect(() => {
    const fetchUserData = async () => {
      const token = getToken()
      try {
        const response = await axios.get("http://localhost:8000/api/v1/auth/user", {
          headers: {
            "Authorization": `Bearer ${token}`,
          },
        });

        if (response.data.status === "Ok") {
          const data = response.data.data;
          setUserData({
            name: data.name || "",
            email: data.email || "",
            profile_image: data.profile_image || "/images/user/default.jpg",
          });

          setEditFormData({
            name: data.name || "",
            email: data.email || "",
            password: "",
            profile_image: null,
          });
        } else {
          throw new Error(response.data.message || "Unknown error");
        }
      } catch (err: any) {
        if (err.response) {
          setError(`Server Error: ${err.response.status} - ${err.response.data.message || err.response.statusText}`);
        } else if (err.request) {
          setError("No response from server. Check your network or backend.");
        } else {
          setError(err.message);
        }
      } finally {
        setLoading(false);
      }
    };

    fetchUserData();
  }, []);

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      setEditFormData(prev => ({ ...prev, profile_image: file }));
      setError(null);
    }
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setEditFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSave = async () => {
    setSubmitting(true);
    setError(null);

    try {
      const token = getToken()

      const formData = new FormData();
      formData.append('_method', 'PUT');
      formData.append('name', editFormData.name);
      formData.append('email', editFormData.email);

      if (editFormData.password) {
        formData.append('password', editFormData.password);
      }

      if (editFormData.profile_image) {
        formData.append('profile_image', editFormData.profile_image);
      }

      const response = await axios.post(
        "http://localhost:8000/api/v1/auth/user-update",
        formData,
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'multipart/form-data',
          },
        }
      );

      if (response.data.status === "success") {
        setUserData({
          name: response.data.data.user.name,
          email: response.data.data.user.email,
          profile_image: response.data.data.user.profile_image || "/images/user/default.jpg",
        });

        // Reset form
        setEditFormData({
          name: response.data.data.user.name,
          email: response.data.data.user.email,
          password: "",
          profile_image: null,
        });

        // Close modal
        closeModal();
      } else {
        setError(response.data.message || "Update failed");
      }
    } catch (err: any) {
      if (err.response) {
        setError(`Server Error: ${err.response.status} - ${err.response.data.message || err.response.statusText}`);
      } else if (err.request) {
        setError("No response from server. Check your network or backend.");
      } else {
        setError(err.message);
      }
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="p-5 border h-full border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6 flex items-center justify-center">
        <div className="animate-spin rounded-full h-10 w-10 border-t-2 border-blue-500"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="p-5 border h-full border-red-300 bg-red-50 rounded-2xl dark:border-red-700 dark:bg-red-900 lg:p-6">
        <p className="text-red-600 dark:text-red-300">Error: {error}</p>
      </div>
    );
  }

  return (
    <div className="p-5 border h-full border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
      <h3 className="mb-5 text-center text-xl font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
        Profile User
      </h3>
      <div className="flex justify-center items-center">
        <div className="w-32 h-32 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
          <img src={userData.profile_image || "/images/user/default.jpg"} alt="user" className="w-32 h-32"  />
        </div>
      </div>

      <div className="flex flex-col gap-6 mt-10 lg:flex-row lg:items-start lg:justify-between">
        <div>
          <h4 className="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
            Personal Information
          </h4>

          <div className="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
            <div>
              <p className="mb-2 text-md font-medium leading-normal text-gray-500 dark:text-gray-400">
                Username
              </p>
              <p className="text-sm text-gray-800 dark:text-white/90">
                {userData.name}
              </p>
            </div>

            <div>
              <p className="mb-2 text-md font-medium leading-normal text-gray-500 dark:text-gray-400">
                Email address
              </p>
              <p className="text-sm text-gray-800 dark:text-white/90">
                {userData.email}
              </p>
            </div>

            <div>
              <p className="mb-2 text-md font-medium leading-normal text-gray-500 dark:text-gray-400">
                Password
              </p>
              <p className="text-sm text-gray-800 dark:text-white/90">
                ****
              </p>
            </div>
          </div>
        </div>

        <button
          onClick={openModal}
          className="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto"
        >
          <FaPencilAlt className="size-3.5" />
          Edit
        </button>
      </div>

      <Modal isOpen={isOpen} onClose={closeModal} className="max-w-[700px] mt-36 lg:mt-32">
        <div className="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
          <div className="px-2 pr-14">
            <h4 className="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
              Edit Personal Information
            </h4>
          </div>
          <form className="flex flex-col">
            <div className="h-[520px] overflow-hidden px-2 pb-3">
              <div className="mt-7">
                <div className="space-y-5">
                  {/* Username */}
                  <div>
                    <Label>Username</Label>
                    <Input
                      type="text"
                      value={editFormData.name}
                      onChange={handleInputChange}
                      name="name"
                    />
                  </div>
                  {/* Email Address */}
                  <div>
                    <Label>Email Address</Label>
                    <Input
                      type="text"
                      value={editFormData.email}
                      onChange={handleInputChange}
                      name="email"
                    />
                  </div>
                  {/* Password */}
                  <div>
                    <Label>Password</Label>
                    <Input
                      type="password"
                      placeholder="Enter new password"
                      value={editFormData.password}
                      onChange={handleInputChange}
                      name="password"
                    />
                  </div>
                  {/* Image */}
                  <div>
                    <Label>Image</Label>
                    <FileInput onChange={handleFileChange} />
                    
                    {/* Gambar Saat Ini */}
                    {userData.profile_image && (
                      <div className="mt-4">
                        <div className="w-28 h-28 overflow-hidden border border-gray-300 rounded-lg">
                          <img
                            src={userData.profile_image}
                            alt="Current Profile"
                            className="w-full h-full object-cover"
                          />
                        </div>
                        <span className="block mt-2 text-sm text-gray-500">Gambar saat ini</span>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </div>
            {/* Tombol Aksi */}
            <div className="flex items-center gap-3 px-2 mt-6 justify-end">
              <Button size="sm" variant="outline" onClick={closeModal}>
                Tutup
              </Button>
              <Button
                size="sm"
                onClick={handleSave}
                disabled={submitting}
                className={submitting ? "opacity-70 cursor-not-allowed" : ""}
              >
                {submitting ? "Menyimpan..." : "Simpan"}
              </Button>
            </div>
          </form>
        </div>
      </Modal>
    </div>
  );
}